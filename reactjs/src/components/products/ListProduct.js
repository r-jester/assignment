import React from 'react';

function ListProduct({ productList = [] }) {
  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Product List</h2>
      <table style={styles.table}>
        <thead>
          <tr>
            <th style={styles.th}>Name</th>
            <th style={styles.th}>Price</th>
            <th style={styles.th}>Quantity</th>
            <th style={styles.th}>Category</th>
            <th style={styles.th}>Manufacturer</th>
            <th style={styles.th}>Expiry Date</th>
          </tr>
        </thead>
        <tbody>
          {productList.map((product, index) => (
            <tr key={index} style={styles.tr}>
              <td style={styles.td}>{product.name}</td>
              <td style={styles.td}>${product.price}</td>
              <td style={styles.td}>{product.quantity}</td>
              <td style={styles.td}>{product.category}</td>
              <td style={styles.td}>{product.manufacturer}</td>
              <td style={styles.td}>{product.expiryDate}</td>
            </tr>
          ))}
        </tbody>
      </table>
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
  },
  heading: {
    textAlign: 'center',
    color: '#333',
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
};

export default ListProduct;