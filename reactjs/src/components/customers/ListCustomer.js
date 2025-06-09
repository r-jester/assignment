import React, { useEffect, useState, useCallback } from 'react';

function ListCustomer({ customerList: initialCustomers = [], onUpdateCustomerList }) {
  const [editingCustomer, setEditingCustomer] = useState(null); // State to track the customer being edited
  const [isModalOpen, setIsModalOpen] = useState(false); // State to control modal visibility

  const handleDeleteRow = useCallback((row) => {
    const confirmDelete = window.confirm('Are you sure you want to delete this customer?');
    if (confirmDelete) {
      const tableBody = document.getElementById('customer-table-body');
      const rowId = row.getAttribute('data-id');

      // Remove the row from the DOM
      tableBody.removeChild(row);

      // Update the parent component if needed
      const updatedList = initialCustomers.filter((customer) => customer.id !== rowId);
      if (typeof onUpdateCustomerList === 'function') {
        onUpdateCustomerList(updatedList);
      }
    }
  }, [initialCustomers, onUpdateCustomerList]);

  const handleEditRow = useCallback((customer) => {
    // Set the customer being edited and open the modal
    setEditingCustomer(customer);
    setIsModalOpen(true);
  }, []);

  const renderCustomerRows = useCallback((customers) => {
    const tableBody = document.getElementById('customer-table-body');
    if (!tableBody) return;

    tableBody.innerHTML = ''; // Clear existing rows

    customers.forEach((customer) => {
      const row = document.createElement('tr');
      row.setAttribute('data-id', customer.id); // Attach customer ID to the row

      // Append cells for each customer field
      Object.keys(customer).forEach((key) => {
        if (key !== 'id') {
          const cell = document.createElement('td');
          cell.textContent = customer[key];
          row.appendChild(cell);
        }
      });

      // Create Actions cell
      const actionsCell = document.createElement('td');

      // Add Edit button
      const editButton = document.createElement('button');
      editButton.textContent = 'Edit';
      editButton.style = styles.buttonEdit;
      editButton.addEventListener('click', () => handleEditRow(customer));

      // Add Delete button
      const deleteButton = document.createElement('button');
      deleteButton.textContent = 'Delete';
      deleteButton.style = styles.buttonDelete;
      deleteButton.addEventListener('click', () => handleDeleteRow(row));

      actionsCell.appendChild(editButton);
      actionsCell.appendChild(deleteButton);
      row.appendChild(actionsCell);

      // Append the row to the table body
      tableBody.appendChild(row);
    });
  }, [handleDeleteRow, handleEditRow]);

  useEffect(() => {
    // Sync customerList rows with the DOM on initial render
    renderCustomerRows(initialCustomers);
  }, [initialCustomers, renderCustomerRows]);



  const handleSaveEdit = () => {
    if (editingCustomer) {
      // Update the customer list with the edited customer
      const updatedList = initialCustomers.map((customer) =>
        customer.id === editingCustomer.id ? editingCustomer : customer
      );

      // Update the parent component
      if (typeof onUpdateCustomerList === 'function') {
        onUpdateCustomerList(updatedList);
      }

      // Re-render the table rows
      renderCustomerRows(updatedList);

      // Close the modal
      setIsModalOpen(false);
    }
  };

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setEditingCustomer((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Customer List</h2>
      <table style={styles.table}>
        <thead>
          <tr>
            <th style={styles.th}>Name</th>
            <th style={styles.th}>Email</th>
            <th style={styles.th}>Phone</th>
            <th style={styles.th}>Address</th>
            <th style={styles.th}>Date of Birth</th>
            <th style={styles.th}>Loyalty Points</th>
            <th style={styles.th}>Membership Level</th>
            <th style={styles.th}>Actions</th>
          </tr>
        </thead>
        <tbody id="customer-table-body"></tbody>
      </table>

      {/* Edit Modal */}
      {isModalOpen && (
        <div style={styles.modalOverlay}>
          <div style={styles.modal}>
            <h3 style={styles.modalHeader}>Edit Customer</h3>
            <div style={styles.modalContent}>
              <form onSubmit={(e) => e.preventDefault()}>
                <label style={styles.label}>
                  Name:
                  <input
                    type="text"
                    name="name"
                    value={editingCustomer.name}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Email:
                  <input
                    type="email"
                    name="email"
                    value={editingCustomer.email}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Phone:
                  <input
                    type="text"
                    name="phone"
                    value={editingCustomer.phone}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Address:
                  <input
                    type="text"
                    name="address"
                    value={editingCustomer.address}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Date of Birth:
                  <input
                    type="date"
                    name="dob"
                    value={editingCustomer.dob}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Loyalty Points:
                  <input
                    type="number"
                    name="loyaltyPoints"
                    value={editingCustomer.loyaltyPoints}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Membership Level:
                  <input
                    type="text"
                    name="membershipLevel"
                    value={editingCustomer.membershipLevel}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
              </form>
            </div>
            <div style={styles.modalFooter}>
              <button style={styles.buttonSave} onClick={handleSaveEdit}>
                Save
              </button>
              <button
                style={styles.buttonCancel}
                onClick={() => setIsModalOpen(false)}
              >
                Cancel
              </button>
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
  buttonEdit: `
    background-color: #ffc107;
    color: #000;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
    margin-right: 5px;
  `,
  buttonDelete: `
    background-color: #dc3545;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 3px;
    cursor: pointer;
  `,
  modalOverlay: {
    position: 'fixed',
    top: 0,
    left: 0,
    width: '100%',
    height: '100%',
    backgroundColor: 'rgba(0, 0, 0, 0.5)',
    display: 'flex',
    justifyContent: 'center',
    alignItems: 'center',
    zIndex: 1000,
  },
  modal: {
    backgroundColor: '#fff',
    borderRadius: '8px',
    width: '400px',
    maxHeight: '70vh',
    overflow: 'hidden',
    display: 'flex',
    flexDirection: 'column',
    boxShadow: '0 4px 10px rgba(0, 0, 0, 0.2)',
  },
  modalHeader: {
    padding: '16px',
    borderBottom: '1px solid #eee',
    backgroundColor: '#f8f9fa',
    fontSize: '18px',
    fontWeight: 'bold',
    color: '#333',
  },
  modalContent: {
    padding: '16px',
    overflowY: 'auto',
    flex: 1,
  },
  label: {
    display: 'block',
    marginBottom: '10px',
    fontSize: '14px',
    color: '#555',
  },
  input: {
    width: '100%',
    padding: '8px',
    marginTop: '4px',
    borderRadius: '4px',
    border: '1px solid #ccc',
    fontSize: '14px',
  },
  modalFooter: {
    padding: '16px',
    borderTop: '1px solid #eee',
    backgroundColor: '#f8f9fa',
    display: 'flex',
    justifyContent: 'flex-end',
    gap: '8px',
  },
  buttonSave: {
    backgroundColor: '#28a745',
    color: '#fff',
    border: 'none',
    padding: '8px 16px',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '14px',
  },
  buttonCancel: {
    backgroundColor: '#dc3545',
    color: '#fff',
    border: 'none',
    padding: '8px 16px',
    borderRadius: '4px',
    cursor: 'pointer',
    fontSize: '14px',
  },
};

export default ListCustomer;