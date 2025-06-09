import React, { useState } from 'react';

function AddCustomer({ addCustomer }) {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [phone, setPhone] = useState('');
  const [address, setAddress] = useState('');
  const [dob, setDob] = useState('');
  const [loyaltyPoints, setLoyaltyPoints] = useState('');
  const [membershipLevel, setMembershipLevel] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    const newCustomer = { name, email, phone, address, dob, loyaltyPoints, membershipLevel };

    if (typeof addCustomer === 'function') {
      addCustomer(newCustomer);
      setName('');
      setEmail('');
      setPhone('');
      setAddress('');
      setDob('');
      setLoyaltyPoints('');
      setMembershipLevel('');
      alert('Customer added successfully!');
    } else {
      console.error('addCustomer is not a function');
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Add Customer</h2>
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
            Phone:
            <input
              type="tel"
              value={phone}
              onChange={(e) => setPhone(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Address:
            <input
              type="text"
              value={address}
              onChange={(e) => setAddress(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Date of Birth:
            <input
              type="date"
              value={dob}
              onChange={(e) => setDob(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Loyalty Points:
            <input
              type="number"
              value={loyaltyPoints}
              onChange={(e) => setLoyaltyPoints(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Membership Level:
            <select
              value={membershipLevel}
              onChange={(e) => setMembershipLevel(e.target.value)}
              required
              style={styles.input}
            >
              <option value="">Select Level</option>
              <option value="Basic">Basic</option>
              <option value="Silver">Silver</option>
              <option value="Gold">Gold</option>
              <option value="Platinum">Platinum</option>
            </select>
          </label>
        </div>
        <button type="submit" style={styles.button}>Add Customer</button>
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

export default AddCustomer;