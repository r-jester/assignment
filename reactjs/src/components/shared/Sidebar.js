import React, { useState } from "react";
import { Link } from "react-router-dom";

function Sidebar({ isCollapsed }) {
  const [openGroup, setOpenGroup] = useState(null);

  const toggleGroup = (group) => {
    setOpenGroup(openGroup === group ? null : group);
  };

  // Logout function
  const handleLogout = () => {
    localStorage.clear(); // Clear local storage
    window.location.href = "/"; // Redirect to the root route
  };

  const styles = {
    sidebar: {
      width: "100%",
      height: "100vh",
      backgroundColor: "#2c3e50",
      color: "white",
      padding: "20px",
      boxSizing: "border-box",
    },
    dashboardItem: {
      padding: "10px",
      cursor: "pointer",
      fontSize: "1.1em",
      marginBottom: "15px",
      backgroundColor: "#34495e",
      borderRadius: "5px",
      textAlign: "center",
    },
    dashboardItemHover: {
      color: "#1abc9c",
    },
    group: {
      marginBottom: "15px",
    },
    groupHeader: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      cursor: "pointer",
      padding: "10px",
      backgroundColor: "#34495e",
      borderRadius: "5px",
    },
    groupHeaderText: {
      fontSize: "1.1em",
    },
    groupHeaderIcon: {
      transition: "transform 0.3s",
      transform: openGroup ? "rotate(90deg)" : "rotate(0deg)",
    },
    dropdown: {
      paddingLeft: "20px",
      marginTop: "10px",
    },
    dropdownItem: {
      padding: "8px 0",
      cursor: "pointer",
      fontSize: "0.95em",
    },
    dropdownItemHover: {
      color: "#1abc9c",
    },
    logoutButton: {
      background: "none",
      border: "none",
      color: "white",
      fontSize: "0.95em",
      cursor: "pointer",
      padding: "8px 0",
      textAlign: "left",
      width: "100%",
    },
    logoutButtonHover: {
      color: "#1abc9c",
    },
  };

  const groups = [
    {
      name: "Staff",
      items: ["Add Staff", "List Staff"],
    },
    {
      name: "Product",
      items: ["Add Product", "List Product"],
    },
    {
      name: "Customer",
      items: ["Add Customer", "List Customer"],
    },
    {
      name: "Sale",
      items: ["Add Sale", "List Sale"],
    },
    {
      name: "Expense",
      items: ["Add Expense", "List Expense"],
    },
    {
      name: "Pages",
      items: ["Login", "404 Page"],
    },
  ];

  const generatePath = (item) => {
    return item.toLowerCase().replace(/ /g, "-");
  };

  return (
    <div style={styles.sidebar}>
      <Link to="/" style={{ textDecoration: "none", color: "inherit" }}>
        <div
          style={styles.dashboardItem}
          onMouseEnter={(e) =>
            (e.target.style.color = styles.dashboardItemHover.color)
          }
          onMouseLeave={(e) => (e.target.style.color = "white")}
        >
          Dashboard
        </div>
      </Link>

      {groups.map((group, index) => (
        <div key={index} style={styles.group}>
          <div
            style={styles.groupHeader}
            onClick={() => toggleGroup(group.name)}
          >
            <span style={styles.groupHeaderText}>{group.name}</span>
            <span style={styles.groupHeaderIcon}>▶</span>
          </div>
          {openGroup === group.name && (
            <div style={styles.dropdown}>
              {group.items.map((item, idx) => {
                if (item === "Login") {
                  // Handle "Login" item as a logout button
                  return (
                    <button
                      key={idx}
                      onClick={handleLogout}
                      style={styles.logoutButton}
                      onMouseEnter={(e) =>
                        (e.target.style.color = styles.logoutButtonHover.color)
                      }
                      onMouseLeave={(e) => (e.target.style.color = "white")}
                    >
                      {item}
                    </button>
                  );
                } else {
                  // Handle other items as links
                  return (
                    <Link
                      key={idx}
                      to={generatePath(item)}
                      style={{ textDecoration: "none", color: "inherit" }}
                    >
                      <div
                        style={styles.dropdownItem}
                        onMouseEnter={(e) =>
                          (e.target.style.color = styles.dropdownItemHover.color)
                        }
                        onMouseLeave={(e) => (e.target.style.color = "white")}
                      >
                        {item}
                      </div>
                    </Link>
                  );
                }
              })}
            </div>
          )}
        </div>
      ))}
    </div>
  );
}

export default Sidebar;