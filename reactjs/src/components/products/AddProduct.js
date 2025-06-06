import React, { useState } from 'react';

function AddProduct({ addProduct }) {
  const [name, setName] = useState('');
  const [price, setPrice] = useState('');
  const [quantity, setQuantity] = useState('');
  const [category, setCategory] = useState('');
  const [manufacturer, setManufacturer] = useState('');
  const [expiryDate, setExpiryDate] = useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    const newProduct = { name, price, quantity, category, manufacturer, expiryDate };

    if (typeof addProduct === 'function') {
      addProduct(newProduct);
      setName('');
      setPrice('');
      setQuantity('');
      setCategory('');
      setManufacturer('');
      setExpiryDate('');
      alert('Product added successfully!');
    } else {
      console.error('addProduct is not a function');
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Add Product</h2>
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
            Category:
            <select
              value={category}
              onChange={(e) => setCategory(e.target.value)}
              required
              style={styles.input}
            >
              <option value="">Select Category</option>
              <option value="Electronics">Electronics</option>
              <option value="Clothing">Clothing</option>
              <option value="Groceries">Groceries</option>
              <option value="Books">Books</option>
              <option value="Other">Other</option>
            </select>
          </label>
          <label style={styles.label}>
            Manufacturer:
            <input
              type="text"
              value={manufacturer}
              onChange={(e) => setManufacturer(e.target.value)}
              required
              style={styles.input}
            />
          </label>
          <label style={styles.label}>
            Expiry Date:
            <input
              type="date"
              value={expiryDate}
              onChange={(e) => setExpiryDate(e.target.value)}
              required
              style={styles.input}
            />
          </label>
        </div>
        <button type="submit" style={styles.button}>Add Product</button>
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

export default AddProduct;