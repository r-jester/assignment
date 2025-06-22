import React, { useEffect, useState, useCallback, useMemo } from 'react';
import api from '../../services/api';

function ListStaff() {
  const [staffList, setStaffList] = useState({ data: [], current_page: 1, last_page: 1 });
  const [currentPage, setCurrentPage] = useState(1);
  const [editingUser, setEditingUser] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [departments, setDepartments] = useState([]);
  const [positions, setPositions] = useState([]);
  const [error, setError] = useState(null);
  const [loading, setLoading] = useState(false);
  const [fetchedPages, setFetchedPages] = useState(new Set());

  // Memoize storedUser to prevent useCallback recreation
  const storedUser = useMemo(() => JSON.parse(localStorage.getItem('user')) || null, []);

  const fetchUsers = useCallback(async (page) => {
    // Prevent fetching if page was already fetched
    if (fetchedPages.has(page)) return;

    setLoading(true);
    setError(null);
    try {
      console.log('Fetching users, page:', page);
      const { data } = await api.get(`/users?page=${page}`);
      console.log('Users API response:', data);

      setStaffList(data);
      setFetchedPages(prev => new Set(prev).add(page));
    } catch (err) {
      console.error('Fetch error:', err.response ? err.response.data : err.message);
      setError(err.response?.data?.message || 'Failed to load users.');
    } finally {
      setLoading(false);
    }
  }, [fetchedPages]);

  useEffect(() => {
    const fetchData = async () => {
      try {
        const [deptResponse, posResponse] = await Promise.all([
          api.get('/departments'),
          api.get('/positions'),
        ]);
        console.log('Departments:', deptResponse.data);
        console.log('Positions:', posResponse.data);
        setDepartments(deptResponse.data);
        setPositions(posResponse.data);
      } catch (error) {
        console.error('Error fetching dropdowns:', error.response ? error.response.data : error.message);
        setError('Failed to load departments or positions.');
      }
    };
    fetchData();
  }, []);

  useEffect(() => {
    fetchUsers(currentPage);
  }, [currentPage, fetchUsers]);

  const handleDelete = async (id, role) => {
    if (role === 'superadmin') {
      alert('Cannot delete a superadmin user');
      return;
    }
    if (!window.confirm('Are you sure you want to delete this user?')) return;
    try {
      await api.delete(`/users/delete/${id}`);
      setFetchedPages(new Set()); // Reset cache
      fetchUsers(currentPage);
      alert('User deleted successfully');
    } catch (err) {
      console.error('Delete error:', err.response ? err.response.data : err.message);
      alert(err.response?.data?.message || 'Failed to delete user');
    }
  };

  const handleEditClick = (user) => {
    setEditingUser({
      id: user.id || '',
      department_id: user.department_id || '',
      position_id: user.position_id || '',
      phone: user.phone || '',
      username: user.username || '',
      password: '',
      first_name: user.first_name || '',
      last_name: user.last_name || '',
      email: user.email || '',
      hire_date: user.hire_date ? user.hire_date.split('T')[0] : '',
      salary: user.salary || '',
      status: user.status || 'active',
      image: null,
    });
    setShowModal(true);
  };

  const handleInputChange = (field, value) => {
    setEditingUser(prev => ({
      ...prev,
      [field]: value
    }));
  };

  const validateForm = () => {
    const errors = [];
    if (!editingUser.department_id) errors.push('Department is required');
    if (!editingUser.position_id) errors.push('Position is required');
    if (!editingUser.username) errors.push('Username is required');
    if (!editingUser.first_name) errors.push('First Name is required');
    if (!editingUser.last_name) errors.push('Last Name is required');
    if (!editingUser.email) errors.push('Email is required');
    if (!editingUser.hire_date) errors.push('Hire Date is required');
    if (!editingUser.salary) errors.push('Salary is required');
    if (!editingUser.status) errors.push('Status is required');
    
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (editingUser.email && !emailRegex.test(editingUser.email)) {
      errors.push('Please enter a valid email address');
    }
    
    if (editingUser.salary && (isNaN(editingUser.salary) || Number(editingUser.salary) < 0)) {
      errors.push('Salary must be a positive number');
    }
    
    return errors;
  };

  const handleUpdate = async (e) => {
    e.preventDefault();
    if (!editingUser) return;

    const errors = validateForm();
    if (errors.length > 0) {
      alert(`Please fix the following errors:\n${errors.join('\n')}`);
      return;
    }

    try {
      const formData = new FormData();
      formData.append('department_id', editingUser.department_id);
      formData.append('position_id', editingUser.position_id);
      formData.append('phone', editingUser.phone || '');
      formData.append('username', editingUser.username);
      if (editingUser.password) formData.append('password', editingUser.password);
      formData.append('first_name', editingUser.first_name);
      formData.append('last_name', editingUser.last_name);
      formData.append('email', editingUser.email);
      formData.append('hire_date', editingUser.hire_date);
      formData.append('salary', editingUser.salary);
      formData.append('status', editingUser.status);
      if (editingUser.image) formData.append('image', editingUser.image);

      await api.patch(`/users/update/${editingUser.id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' },
      });
      setShowModal(false);
      setEditingUser(null);
      setFetchedPages(new Set()); // Reset cache
      fetchUsers(currentPage);
      alert('User updated successfully');
    } catch (err) {
      console.error('Update error:', err.response ? err.response.data : err.message);
      const errorMessage = err.response?.data?.message || 'Failed to update user';
      alert(errorMessage);
    }
  };

  const handleCloseModal = () => {
    setShowModal(false);
    setEditingUser(null);
  };

  const handlePrev = () => {
    if (staffList.current_page > 1) {
      setCurrentPage(prev => prev - 1);
    }
  };

  const handleNext = () => {
    if (staffList.current_page < staffList.last_page) {
      setCurrentPage(prev => prev + 1);
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>User List</h2>
      {error && <p style={{ color: 'red', textAlign: 'center' }}>{error}</p>}
      {loading ? (
        <p style={{ textAlign: 'center' }}>Loading...</p>
      ) : (
        <>
          {staffList.data.length === 0 ? (
            <p style={{ textAlign: 'center' }}>No users found.</p>
          ) : (
            <table style={styles.table}>
              <thead>
                <tr>
                  <th style={styles.th}>No.</th>
                  <th style={styles.th}>Username</th>
                  <th style={styles.th}>Name</th>
                  <th style={styles.th}>Email</th>
                  <th style={styles.th}>Department</th>
                  <th style={styles.th}>Position</th>
                  <th style={styles.th}>Status</th>
                  <th style={styles.th}>Actions</th>
                </tr>
              </thead>
              <tbody>
                {staffList.data.map((u) => (
                  <tr key={u.id} style={styles.tr}>
                    <td style={styles.td}>{u.id}</td>
                    <td style={styles.td}>{u.username}</td>
                    <td style={styles.td}>{`${u.first_name} ${u.last_name}`}</td>
                    <td style={styles.td}>{u.email}</td>
                    <td style={styles.td}>{u.department?.name || 'N/A'}</td>
                    <td style={styles.td}>{u.position?.name || 'N/A'}</td>
                    <td style={styles.td}>
                      <span style={{
                        ...styles.statusBadge,
                        backgroundColor: u.status === 'active' ? '#28a745' : u.status === 'inactive' ? '#ffc107' : '#dc3545'
                      }}>
                        {u.status}
                      </span>
                    </td>
                    <td style={styles.td}>
                      <button style={styles.editBtn} onClick={() => handleEditClick(u)}>
                        Edit
                      </button>
                      <button style={styles.deleteBtn} onClick={() => handleDelete(u.id, u.role)}>
                        Delete
                      </button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          )}
          <div style={{ marginTop: '20px', textAlign: 'center' }}>
            <button onClick={handlePrev} disabled={staffList.current_page === 1} style={{
              ...styles.navBtn,
              opacity: staffList.current_page === 1 ? 0.5 : 1,
              cursor: staffList.current_page === 1 ? 'not-allowed' : 'pointer'
            }}>
              Previous
            </button>
            <span style={{ margin: '0 10px' }}>
              Page {staffList.current_page || 1} of {staffList.last_page || 1}
            </span>
            <button onClick={handleNext} disabled={staffList.current_page === staffList.last_page} style={{
              ...styles.navBtn,
              opacity: staffList.current_page === staffList.last_page ? 0.5 : 1,
              cursor: staffList.current_page === staffList.last_page ? 'not-allowed' : 'pointer'
            }}>
              Next
            </button>
          </div>
        </>
      )}

      {showModal && editingUser && (
        <div style={styles.modalOverlay} onClick={(e) => e.target === e.currentTarget && handleCloseModal()}>
          <div style={styles.modalContent}>
            <h3 style={styles.modalHeading}>Edit User</h3>
            <form onSubmit={handleUpdate} style={styles.form}>
              <label style={styles.modalLabel}>
                Department:
                <select
                  value={editingUser.department_id || ''}
                  onChange={(e) => handleInputChange('department_id', e.target.value)}
                  style={styles.modalInput}
                  required
                >
                  <option value="">Select Department</option>
                  {departments.map((dept) => (
                    <option key={dept.id} value={dept.id}>
                      {dept.name}
                    </option>
                  ))}
                </select>
              </label>
              <label style={styles.modalLabel}>
                Position:
                <select
                  value={editingUser.position_id || ''}
                  onChange={(e) => handleInputChange('position_id', e.target.value)}
                  style={styles.modalInput}
                  required
                >
                  <option value="">Select Position</option>
                  {positions.map((pos) => (
                    <option key={pos.id} value={pos.id}>
                      {pos.name}
                    </option>
                  ))}
                </select>
              </label>
              <label style={styles.modalLabel}>
                Phone:
                <input
                  type="tel"
                  value={editingUser.phone || ''}
                  onChange={(e) => handleInputChange('phone', e.target.value)}
                  style={styles.modalInput}
                  placeholder="Enter phone number"
                />
              </label>
              <label style={styles.modalLabel}>
                Username:
                <input
                  type="text"
                  value={editingUser.username || ''}
                  onChange={(e) => handleInputChange('username', e.target.value)}
                  style={styles.modalInput}
                  required
                  placeholder="Enter username"
                />
              </label>
              <label style={styles.modalLabel}>
                Password:
                <input
                  type="password"
                  value={editingUser.password || ''}
                  onChange={(e) => handleInputChange('password', e.target.value)}
                  placeholder="Leave blank if unchanged"
                  style={styles.modalInput}
                />
              </label>
              <label style={styles.modalLabel}>
                First Name:
                <input
                  type="text"
                  value={editingUser.first_name || ''}
                  onChange={(e) => handleInputChange('first_name', e.target.value)}
                  style={styles.modalInput}
                  required
                  placeholder="Enter first name"
                />
              </label>
              <label style={styles.modalLabel}>
                Last Name:
                <input
                  type="text"
                  value={editingUser.last_name || ''}
                  onChange={(e) => handleInputChange('last_name', e.target.value)}
                  style={styles.modalInput}
                  required
                  placeholder="Enter last name"
                />
              </label>
              <label style={styles.modalLabel}>
                Email:
                <input
                  type="email"
                  value={editingUser.email || ''}
                  onChange={(e) => handleInputChange('email', e.target.value)}
                  style={styles.modalInput}
                  required
                  placeholder="Enter email address"
                />
              </label>
              <label style={styles.modalLabel}>
                Hire Date:
                <input
                  type="date"
                  value={editingUser.hire_date || ''}
                  onChange={(e) => handleInputChange('hire_date', e.target.value)}
                  style={styles.modalInput}
                  required
                />
              </label>
              <label style={styles.modalLabel}>
                Salary:
                <input
                  type="number"
                  value={editingUser.salary || ''}
                  onChange={(e) => handleInputChange('salary', e.target.value)}
                  style={styles.modalInput}
                  required
                  min="0"
                  step="0.01"
                  placeholder="Enter salary amount"
                />
              </label>
              <label style={styles.modalLabel}>
                Status:
                <select
                  value={editingUser.status || 'active'}
                  onChange={(e) => handleInputChange('status', e.target.value)}
                  style={styles.modalInput}
                  required
                >
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                  <option value="terminated">Terminated</option>
                </select>
              </label>
              <label style={styles.modalLabel}>
                Profile Image:
                <input
                  type="file"
                  accept="image/*"
                  onChange={(e) => handleInputChange('image', e.target.files[0])}
                  style={styles.modalInput}
                />
                <small style={{ color: '#666', fontSize: '12px' }}>
                  Accepted formats: JPG, PNG, GIF (Max 5MB)
                </small>
              </label>
              <div style={styles.modalFooter}>
                <button type="submit" style={styles.saveBtn}>
                  Save Changes
                </button>
                <button type="button" style={styles.cancelBtn} onClick={handleCloseModal}>
                  Cancel
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
}

const styles = {
  container: {
    maxWidth: '1200px',
    margin: '20px auto',
    padding: '20px',
    border: '1px solid #ccc',
    borderRadius: '5px',
    backgroundColor: '#f9f9f9',
    fontFamily: 'Arial, sans-serif',
  },
  heading: {
    textAlign: 'center',
    color: '#333',
    marginBottom: '10px',
  },
  table: {
    width: '100%',
    borderCollapse: 'collapse',
    marginTop: '20px',
  },
  th: {
    backgroundColor: '#28a745',
    color: '#fff',
    padding: '10px',
    textAlign: 'left',
  },
  tr: {
    borderBottom: '1px solid #ccc',
  },
  td: {
    padding: '10px',
    color: '#555',
  },
  statusBadge: {
    padding: '4px 8px',
    borderRadius: '4px',
    color: 'white',
    fontSize: '12px',
    fontWeight: 'bold',
    textTransform: 'uppercase',
  },
  editBtn: {
    padding: '5px 10px',
    marginRight: '5px',
    backgroundColor: '#007bff',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '12px',
  },
  deleteBtn: {
    padding: '5px 10px',
    backgroundColor: '#dc3545',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '12px',
  },
  navBtn: {
    padding: '8px 16px',
    backgroundColor: '#007bff',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
  },
  modalOverlay: {
    position: 'fixed',
    top: 0,
    left: 0,
    width: '100vw',
    height: '100vh',
    backgroundColor: 'rgba(0,0,0,0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
  },
  modalContent: {
    width: '500px',
    maxWidth: '90vw',
    maxHeight: '90vh',
    backgroundColor: '#fff',
    padding: '20px',
    borderRadius: '8px',
    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
    display: 'flex',
    flexDirection: 'column',
    gap: '12px',
    overflowY: 'auto',
  },
  modalHeading: {
    margin: '0 0 10px 0',
    textAlign: 'center',
    color: '#333',
    fontSize: '20px',
  },
  form: {
    display: 'flex',
    flexDirection: 'column',
    gap: '10px',
  },
  modalLabel: {
    display: 'flex',
    flexDirection: 'column',
    fontSize: '14px',
    color: '#555',
    fontWeight: '500',
  },
  modalInput: {
    marginTop: '4px',
    padding: '10px',
    border: '1px solid #ccc',
    borderRadius: '4px',
    fontSize: '14px',
    width: '100%',
    boxSizing: 'border-box',
    transition: 'border-color 0.2s',
  },
  modalFooter: {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '10px',
    marginTop: '10px',
  },
  saveBtn: {
    padding: '10px 20px',
    backgroundColor: '#28a745',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '14px',
    fontWeight: '500',
  },
  cancelBtn: {
    padding: '10px 20px',
    backgroundColor: '#6c757d',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '14px',
    fontWeight: '500',
  },
};

export default ListStaff;