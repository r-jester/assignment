import React, { useState } from 'react';

function ListExpense({ expenseList: initialExpenses = [], onUpdateExpenseList }) {
  // State to manage the list of expenses
  const [expenseList, setExpenseList] = useState(initialExpenses);

  // State to manage the expense being edited
  const [editingExpense, setEditingExpense] = useState(null);

  // Function to handle editing an expense
  const handleEdit = (expense) => {
    setEditingExpense({ ...expense });
  };

  // Function to save changes after editing
  const handleSave = () => {
    if (editingExpense) {
      const updatedExpenses = expenseList.map((e) =>
        e.id === editingExpense.id ? editingExpense : e
      );
      setExpenseList(updatedExpenses);
      if (typeof onUpdateExpenseList === 'function') {
        onUpdateExpenseList(updatedExpenses); // Notify parent component of changes
      }
      setEditingExpense(null); // Close the edit form
    }
  };

  // Function to handle input changes in the edit form
  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setEditingExpense((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  // Function to handle deleting an expense
  const handleDelete = (expenseId) => {
    const confirmDelete = window.confirm('Are you sure you want to delete this expense?');
    if (confirmDelete) {
      const updatedExpenses = expenseList.filter((e) => e.id !== expenseId);
      setExpenseList(updatedExpenses);
      if (typeof onUpdateExpenseList === 'function') {
        onUpdateExpenseList(updatedExpenses); // Notify parent component of changes
      }
    }
  };

  return (
    <div style={styles.container}>
      <h2 style={styles.heading}>Expense List</h2>
      <table style={styles.table}>
        <thead>
          <tr>
            <th style={styles.th}>Description</th>
            <th style={styles.th}>Amount</th>
            <th style={styles.th}>Date</th>
            <th style={styles.th}>Category</th>
            <th style={styles.th}>Payment Method</th>
            <th style={styles.th}>Actions</th>
          </tr>
        </thead>
        <tbody>
          {expenseList.map((expense) => (
            <tr key={expense.id} style={styles.tr}>
              <td style={styles.td}>{expense.description}</td>
              <td style={styles.td}>${expense.amount}</td>
              <td style={styles.td}>{expense.date}</td>
              <td style={styles.td}>{expense.category}</td>
              <td style={styles.td}>{expense.paymentMethod}</td>
              <td style={styles.td}>
                <button
                  style={styles.buttonEdit}
                  onClick={() => handleEdit(expense)}
                >
                  Edit
                </button>
                <button
                  style={styles.buttonDelete}
                  onClick={() => handleDelete(expense.id)}
                >
                  Delete
                </button>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {/* Edit Form Modal */}
      {editingExpense && (
        <div style={styles.modalOverlay}>
          <div style={styles.modal}>
            <h3 style={styles.modalHeader}>Edit Expense</h3>
            <div style={styles.modalContent}>
              <form onSubmit={(e) => e.preventDefault()}>
                <label style={styles.label}>
                  Description:
                  <input
                    type="text"
                    name="description"
                    value={editingExpense.description}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Amount:
                  <input
                    type="number"
                    name="amount"
                    value={editingExpense.amount}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Date:
                  <input
                    type="date"
                    name="date"
                    value={editingExpense.date}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Category:
                  <input
                    type="text"
                    name="category"
                    value={editingExpense.category}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
                <label style={styles.label}>
                  Payment Method:
                  <input
                    type="text"
                    name="paymentMethod"
                    value={editingExpense.paymentMethod}
                    onChange={handleInputChange}
                    style={styles.input}
                  />
                </label>
              </form>
            </div>
            <div style={styles.modalFooter}>
              <button style={styles.buttonSave} onClick={handleSave}>
                Save
              </button>
              <button
                style={styles.buttonCancel}
                onClick={() => setEditingExpense(null)}
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
  tr: {
    borderBottom: '1px solid #ccc',
  },
  td: {
    padding: '10px',
    color: '#555',
  },
  buttonEdit: {
    backgroundColor: '#ffc107',
    color: '#000',
    border: 'none',
    padding: '5px 10px',
    borderRadius: '3px',
    cursor: 'pointer',
    marginRight: '5px',
  },
  buttonDelete: {
    backgroundColor: '#dc3545',
    color: '#fff',
    border: 'none',
    padding: '5px 10px',
    borderRadius: '3px',
    cursor: 'pointer',
  },
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

export default ListExpense;