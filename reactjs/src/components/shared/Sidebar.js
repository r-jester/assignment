import React, { useState } from "react";
import { Link } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faChevronRight,
  faChevronDown,
  faSignOutAlt,
  faUsers,
  faBox,
  faUserFriends,
  faShoppingCart,
  faMoneyBill,
  faFileAlt,
  faHome,
} from "@fortawesome/free-solid-svg-icons";

function Sidebar({ isCollapsed, username }) {
  const [openGroup, setOpenGroup] = useState(null);

  const toggleGroup = (group) => {
    setOpenGroup(openGroup === group ? null : group);
  };

  const handleLogout = () => {
    localStorage.clear();
    window.location.href = "/";
  };

  const iconMap = {
    Staff: faUsers,
    Product: faBox,
    Customer: faUserFriends,
    Sale: faShoppingCart,
    Expense: faMoneyBill,
    Pages: faFileAlt,
  };

  const groups = [
    { name: "Staff", items: ["Add Staff", "List Staff"] },
    { name: "Product", items: ["Add Product", "List Product"] },
    { name: "Customer", items: ["Add Customer", "List Customer"] },
    { name: "Sale", items: ["Add Sale", "List Sale"] },
    { name: "Expense", items: ["Add Expense", "List Expense"] },
    { name: "Pages", items: ["Login", "404 Page"] },
  ];

  const generatePath = (item) => item.toLowerCase().replace(/ /g, "-");

  const styles = {
    sidebar: {
      position: "fixed",
      top: 0,
      bottom: 0,
      left: 0,
      width: "250px",
      backgroundColor: "#2c3e50",
      color: "white",
      padding: "20px 10px",
      boxSizing: "border-box",
      overflowY: "scroll",
      overflowX: "hidden",
      userSelect: "none",
      zIndex: 1000,
      display: "flex",
      flexDirection: "column",
      height: "100vh",
      transform: isCollapsed ? "translateX(-100%)" : "translateX(0)",
      transition: "transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1)",
      visibility: isCollapsed ? "hidden" : "visible",
      opacity: isCollapsed ? 0 : 1,
      transitionProperty: "transform, opacity, visibility",
      transitionDuration: "0.4s, 0.4s, 0s",
      transitionDelay: isCollapsed ? "0s, 0s, 0.4s" : "0s, 0s, 0s",
      transitionTimingFunction: "cubic-bezier(0.25, 0.8, 0.25, 1)",
      scrollbarWidth: "none", // Firefox
      msOverflowStyle: "none", // IE/Edge
    },
    sidebarContent: {
      opacity: isCollapsed ? 0 : 1,
      transform: isCollapsed ? "translateX(-20px)" : "translateX(0)",
      transition: "opacity 0.3s ease, transform 0.3s ease",
      transitionDelay: isCollapsed ? "0s" : "0.1s",
    },
    username: {
      fontSize: "1.2em",
      fontWeight: "bold",
      marginBottom: "30px",
      textAlign: "center",
    },
    dashboardItem: {
      padding: "10px",
      cursor: "pointer",
      fontSize: "1.1em",
      marginBottom: "15px",
      backgroundColor: "#34495e",
      borderRadius: "5px",
      textAlign: "center",
      whiteSpace: "nowrap",
      overflow: "hidden",
      textOverflow: "ellipsis",
      transition: "all 0.3s ease",
    },
    group: {
      marginBottom: "15px",
      flexShrink: 0,
    },
    groupHeader: {
      display: "flex",
      justifyContent: "space-between",
      alignItems: "center",
      cursor: "pointer",
      padding: "10px",
      backgroundColor: "#34495e",
      borderRadius: "5px",
      userSelect: "none",
      transition: "all 0.3s ease",
    },
    groupHeaderText: {
      fontSize: "1.1em",
      flexGrow: 1,
      marginLeft: "10px",
    },
    groupHeaderIcon: {
      transition: "transform 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)",
      color: "white",
    },
    dropdown: {
      paddingLeft: "20px",
      marginTop: "10px",
      overflow: "hidden",
      transition: "max-height 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), opacity 0.3s ease",
    },
    dropdownItem: {
      padding: "8px 0",
      cursor: "pointer",
      fontSize: "0.95em",
      whiteSpace: "nowrap",
      color: "white",
      transition: "all 0.2s ease",
      borderRadius: "3px",
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
      display: "flex",
      alignItems: "center",
      gap: "8px",
      transition: "all 0.2s ease",
      borderRadius: "3px",
    },
  };

  return (
    <>
      <style>
        {`
          nav::-webkit-scrollbar {
            display: none;
          }
        `}
      </style>

      <nav style={styles.sidebar} aria-label="Sidebar Navigation">
        <div style={styles.sidebarContent}>
          <div style={styles.username}>{username || "Loading..."}</div>

          <Link
            to="/"
            style={{
              ...styles.dashboardItem,
              display: "flex",
              alignItems: "center",
              justifyContent: "flex-start",
              gap: "10px",
              textDecoration: "none",
              color: "white",
              marginBottom: "25px",
            }}
            aria-label="Dashboard"
            onMouseOver={(e) => {
              e.currentTarget.style.backgroundColor = "#3a5169";
              e.currentTarget.style.transform = "translateY(-2px)";
            }}
            onMouseOut={(e) => {
              e.currentTarget.style.backgroundColor = "#34495e";
              e.currentTarget.style.transform = "translateY(0)";
            }}
          >
            <FontAwesomeIcon icon={faHome} />
            <span>Dashboard</span>
          </Link>

          {groups.map((group, index) => (
            <div key={index} style={styles.group}>
              <div
                style={styles.groupHeader}
                onClick={() => toggleGroup(group.name)}
                tabIndex={0}
                role="button"
                onKeyDown={(e) => {
                  if (e.key === "Enter" || e.key === " ") toggleGroup(group.name);
                }}
                onMouseOver={(e) => {
                  e.currentTarget.style.backgroundColor = "#3a5169";
                }}
                onMouseOut={(e) => {
                  e.currentTarget.style.backgroundColor = "#34495e";
                }}
                aria-expanded={openGroup === group.name}
              >
                <FontAwesomeIcon icon={iconMap[group.name]} />
                <span style={styles.groupHeaderText}>{group.name}</span>
                <FontAwesomeIcon
                  icon={openGroup === group.name ? faChevronDown : faChevronRight}
                  style={{
                    ...styles.groupHeaderIcon,
                    transform: openGroup === group.name ? "rotate(90deg)" : "rotate(0deg)",
                  }}
                />
              </div>

              <div
                style={{
                  ...styles.dropdown,
                  maxHeight: openGroup === group.name ? "200px" : "0",
                  opacity: openGroup === group.name ? 1 : 0,
                }}
              >
                {group.items.map((item, idx) =>
                  item === "Login" ? (
                    <button
                      key={idx}
                      onClick={handleLogout}
                      style={styles.logoutButton}
                      onMouseOver={(e) => {
                        e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
                        e.currentTarget.style.paddingLeft = "5px";
                      }}
                      onMouseOut={(e) => {
                        e.currentTarget.style.backgroundColor = "transparent";
                        e.currentTarget.style.paddingLeft = "0";
                      }}
                      aria-label="Logout"
                    >
                      <FontAwesomeIcon icon={faSignOutAlt} />
                      {item}
                    </button>
                  ) : (
                    <Link key={idx} to={generatePath(item)} style={{ textDecoration: "none" }}>
                      <div
                        style={styles.dropdownItem}
                        onMouseOver={(e) => {
                          e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
                          e.currentTarget.style.paddingLeft = "5px";
                        }}
                        onMouseOut={(e) => {
                          e.currentTarget.style.backgroundColor = "transparent";
                          e.currentTarget.style.paddingLeft = "0";
                        }}
                      >
                        {item}
                      </div>
                    </Link>
                  )
                )}
              </div>
            </div>
          ))}
        </div>
      </nav>
    </>
  );
}

export default Sidebar;