import React from 'react';

function ListSale({ saleList = [] }) {
  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Sale List</h2>
      <table style={styles.table}>
        <thead>
          <tr>
            <th style={styles.th}>Product Name</th>
            <th style={styles.th}>Quantity</th>
            <th style={styles.th}>Price</th>
            <th style={styles.th}>Customer Name</th>
            <th style={styles.th}>Sale Date</th>
            <th style={styles.th}>Payment Method</th>
          </tr>
        </thead>
        <tbody>
          {saleList.map((sale, index) => (
            <tr key={index} style={styles.tr}>
              <td style={styles.td}>{sale.productName}</td>
              <td style={styles.td}>{sale.quantity}</td>
              <td style={styles.td}>${sale.price}</td>
              <td style={styles.td}>{sale.customerName}</td>
              <td style={styles.td}>{sale.saleDate}</td>
              <td style={styles.td}>{sale.paymentMethod}</td>
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

export default ListSale;