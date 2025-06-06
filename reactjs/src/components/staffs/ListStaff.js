import React, { useEffect, useState } from 'react';
import api from '../../services/api';

function ListStaff() {
  const [staffList, setStaffList] = useState([]);
  const [editingUser, setEditingUser] = useState(null);
  const [showModal, setShowModal] = useState(false);

  const fetchUsers = async () => {
    try {
      const { data } = await api.get('/users');
      setStaffList(data);
    } catch (err) {
      console.error('Fetch error:', err);
    }
  };

  useEffect(() => { fetchUsers(); }, []);

  const handleDelete = async (id) => {
    if (!window.confirm('Are you sure you want to delete this user?')) return;
    try {
      await api.delete(`/users/delete/${id}`);
      fetchUsers();
    } catch (err) {
      console.error('Delete error:', err);
    }
  };

  const handleEditClick = (user) => {
    setEditingUser({ ...user, password: '' });
    setShowModal(true);
  };

  const handleUpdate = async () => {
    try {
      await api.patch(
        `/users/update/${editingUser.id}`,
        {
          txtname: editingUser.name,
          txtemail: editingUser.email,
          ...(editingUser.password ? { txtpass: editingUser.password } : {})
        }
      );
      setShowModal(false);
      fetchUsers();
    } catch (err) {
      console.error('Update error:', err);
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>User List</h2>
      <table style={styles.table}>
        <thead>
          <tr>
            <th style={styles.th}>ID</th>
            <th style={styles.th}>Name</th>
            <th style={styles.th}>Email</th>
            <th style={styles.th}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {staffList.map((u) => (
            <tr key={u.id} style={styles.tr}>
              <td style={styles.td}>{u.id}</td>
              <td style={styles.td}>{u.name}</td>
              <td style={styles.td}>{u.email}</td>
              <td style={styles.td}>
                <button style={styles.editBtn} onClick={() => handleEditClick(u)}>Edit</button>
                <button style={styles.deleteBtn} onClick={() => handleDelete(u.id)}>Delete</button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {showModal && editingUser && (
        <div style={styles.modalOverlay}>
          <div style={styles.modalContent}>
            <h3 style={styles.modalHeading}>Edit User</h3>
            <label style={styles.modalLabel}>Name:
              <input
                type="text"
                value={editingUser.name}
                onChange={(e) =>
                  setEditingUser({ ...editingUser, name: e.target.value })
                }
                style={styles.modalInput}
              />
            </label>
            <label style={styles.modalLabel}>Email:
              <input
                type="email"
                value={editingUser.email}
                onChange={(e) =>
                  setEditingUser({ ...editingUser, email: e.target.value })
                }
                style={styles.modalInput}
              />
            </label>
            <label style={styles.modalLabel}>Password:
              <input
                type="password"
                placeholder="Leave blank if unchanged"
                onChange={(e) =>
                  setEditingUser({ ...editingUser, password: e.target.value })
                }
                style={styles.modalInput}
              />
            </label>
            <div style={styles.modalFooter}>
              <button style={styles.saveBtn} onClick={handleUpdate}>Save</button>
              <button style={styles.cancelBtn} onClick={() => setShowModal(false)}>Cancel</button>
            </div>
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
  editBtn: {
    padding: '5px 10px',
    marginRight: '5px',
    backgroundColor: '#007bff',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
  },
  deleteBtn: {
    padding: '5px 10px',
    backgroundColor: '#dc3545',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
  },
  modalOverlay: {
    position: 'fixed',
    top: 0, left: 0,
    width: '100vw',
    height: '100vh',
    backgroundColor: 'rgba(0,0,0,0.5)',
    display: 'flex',
    alignItems: 'center',
    justifyContent: 'center',
    zIndex: 1000,
  },
  modalContent: {
    width: '360px',
    backgroundColor: '#fff',
    padding: '20px',
    borderRadius: '8px',
    boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
    display: 'flex',
    flexDirection: 'column',
    gap: '12px',
  },
  modalHeading: {
    margin: 0,
    textAlign: 'center',
    color: '#333',
  },
  modalLabel: {
    display: 'flex',
    flexDirection: 'column',
    fontSize: '14px',
    color: '#555',
  },
  modalInput: {
    marginTop: '4px',
    padding: '8px',
    border: '1px solid #ccc',
    borderRadius: '4px',
    fontSize: '14px',
  },
  modalFooter: {
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '10px',
    marginTop: '10px',
  },
  saveBtn: {
    padding: '8px 16px',
    backgroundColor: '#28a745',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
  },
  cancelBtn: {
    padding: '8px 16px',
    backgroundColor: '#6c757d',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
  },
};

export default ListStaff;
