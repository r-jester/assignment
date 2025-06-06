import React, { useState } from 'react';

function AddSale({ addSale }) {
  const [productName, setProductName] = useState('');
  const [quantity, setQuantity] = useState('');
  const [price, setPrice] = useState('');
  const [customerName, setCustomerName] = useState('');
  const [saleDate, setSaleDate] = useState('');
  const [paymentMethod, setPaymentMethod] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    const newSale = { productName, quantity, price, customerName, saleDate, paymentMethod };

    if (typeof addSale === 'function') {
      addSale(newSale);
      setProductName('');
      setQuantity('');
      setPrice('');
      setCustomerName('');
      setSaleDate('');
      setPaymentMethod('');
      alert('Sale added successfully!');
    } else {
      console.error('addSale is not a function');
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Add Sale</h2>
      <form onSubmit={handleSubmit} style={styles.form}>
        <div style={styles.grid}>
          <label style={styles.label}>
            Product Name:
            <input
              type="text"
              value={productName}
              onChange={(e) => setProductName(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Quantity:
            <input
              type="number"
              value={quantity}
              onChange={(e) => setQuantity(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Price:
            <input
              type="number"
              value={price}
              onChange={(e) => setPrice(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Customer Name:
            <input
              type="text"
              value={customerName}
              onChange={(e) => setCustomerName(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Sale Date:
            <input
              type="date"
              value={saleDate}
              onChange={(e) => setSaleDate(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Payment Method:
            <select
              value={paymentMethod}
              onChange={(e) => setPaymentMethod(e.target.value)}
              required
              style={styles.input}
            >
              <option value="">Select Method</option>
              <option value="Cash">Cash</option>
              <option value="Credit Card">Credit Card</option>
              <option value="Debit Card">Debit Card</option>
              <option value="Online Payment">Online Payment</option>
            </select>
          </label>
        </div>
        <button type="submit" style={styles.button}>Add Sale</button>
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

export default AddSale;