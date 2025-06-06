import React, { useState, useEffect } from 'react';
import api from '../../services/api';

function AddStaff({ refreshStaff }) {
  const [id, setId] = useState(null);
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (id) {
        // Update
        await api.post('/users/update', { id, name, email, password });
        alert('User updated successfully');
      } else {
        // Create
        await api.post('/users/create', { name, email, password });
        alert('User added successfully');
      }
      setId(null);
      setName('');
      setEmail('');
      setPassword('');
      refreshStaff();
    } catch (error) {
      console.error(error);
      alert('Failed to submit');
    }
  };

  // For editing data passed from parent
  useEffect(() => {
    const editData = JSON.parse(localStorage.getItem('editUser'));
    if (editData) {
      setId(editData.id);
      setName(editData.name);
      setEmail(editData.email);
      setPassword('');
      localStorage.removeItem('editUser');
    }
  }, []);

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>{id ? 'Edit User' : 'Add User'}</h2>
      <form onSubmit={handleSubmit} style={styles.form}>
        <div style={styles.grid}>
          <label style={styles.label}>
            Name:
            <input
              type="text"
              value={name}
              onChange={(e) => setName(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Email:
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Password:
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required={!id} // Required only when adding
              style={styles.input}
            />
          </label>
        </div>
        <button type="submit" style={styles.button}>{id ? 'Update User' : 'Add User'}</button>
      </form>
    </div>
  );
}

const styles = {
  container: {
    maxWidth: '800px',
    margin: '0 auto',
    padding: '20px',
    border: '1px solid #ccc',
    borderRadius: '5px',
    backgroundColor: '#f9f9f9',
  },
  heading: {
    textAlign: 'center',
    color: '#333',
  },
  form: {
    display: 'flex',
    flexDirection: 'column',
  },
  grid: {
    display: 'grid',
    gridTemplateColumns: 'repeat(3, 1fr)',
    gap: '10px',
  },
  label: {
    margin: '10px 0 5px',
    color: '#555',
  },
  input: {
    padding: '8px',
    marginBottom: '10px',
    border: '1px solid #ccc',
    borderRadius: '4px',
    width: '100%',
  },
  button: {
    padding: '10px',
    backgroundColor: '#28a745',
    color: '#fff',
    border: 'none',
    borderRadius: '4px',
    cursor: 'pointer',
    marginTop: '10px',
  },
};

export default AddStaff;
