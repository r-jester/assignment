import React from "react";
import { useNavigate } from "react-router-dom";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faBars, faUserCircle, faHome, faUsers, faBox, faSignOutAlt } from "@fortawesome/free-solid-svg-icons";

const Header = ({ toggleSidebar, username }) => {
  const navigate = useNavigate();

  const handleLogout = () => {
    localStorage.clear();
    window.location.href = "/";
  };

  const iconStyle = {
    fontSize: 20,
    cursor: "pointer",
    padding: "8px",
    borderRadius: "4px",
    transition: "all 0.2s ease",
  };

  return (
    <header
      style={{
        height: 60,
        background: "linear-gradient(90deg, #2563eb 0%, #3b82f6 50%, #2563eb 100%)",
        color: "white",
        display: "flex",
        alignItems: "center",
        padding: "0 20px",
        justifyContent: "space-between",
        boxShadow: "0 4px 10px rgba(0, 0, 0, 0.15)",
        position: "sticky",
        top: 0,
        zIndex: 1001,
        boxSizing: "border-box",
        backdropFilter: "blur(10px)",
        flexShrink: 0,
      }}
    >
      <button
        onClick={toggleSidebar}
        style={{
          background: "none",
          border: "none",
          color: "white",
          fontSize: 24,
          cursor: "pointer",
          padding: "8px",
          borderRadius: "4px",
          transition: "all 0.2s ease",
          display: "flex",
          alignItems: "center",
          justifyContent: "center",
        }}
        title="Toggle Sidebar"
        aria-label="Toggle sidebar"
        onMouseOver={(e) => {
          e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
          e.currentTarget.style.transform = "scale(1.05)";
        }}
        onMouseOut={(e) => {
          e.currentTarget.style.backgroundColor = "transparent";
          e.currentTarget.style.transform = "scale(1)";
        }}
      >
        <FontAwesomeIcon icon={faBars} />
      </button>

      <div style={{ display: "flex", alignItems: "center", gap: "15px" }}>
        <FontAwesomeIcon
          icon={faHome}
          style={iconStyle}
          title="Dashboard"
          onClick={() => navigate("/")}
          onMouseOver={(e) => {
            e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
            e.currentTarget.style.transform = "scale(1.05)";
          }}
          onMouseOut={(e) => {
            e.currentTarget.style.backgroundColor = "transparent";
            e.currentTarget.style.transform = "scale(1)";
          }}
        />
        <FontAwesomeIcon
          icon={faUsers}
          style={iconStyle}
          title="List Staff"
          onClick={() => navigate("/list-staff")}
          onMouseOver={(e) => {
            e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
            e.currentTarget.style.transform = "scale(1.05)";
          }}
          onMouseOut={(e) => {
            e.currentTarget.style.backgroundColor = "transparent";
            e.currentTarget.style.transform = "scale(1)";
          }}
        />
        <FontAwesomeIcon
          icon={faBox}
          style={iconStyle}
          title="List Product"
          onClick={() => navigate("/list-product")}
          onMouseOver={(e) => {
            e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
            e.currentTarget.style.transform = "scale(1.05)";
          }}
          onMouseOut={(e) => {
            e.currentTarget.style.backgroundColor = "transparent";
            e.currentTarget.style.transform = "scale(1)";
          }}
        />
        <div
          style={{
            display: "flex",
            alignItems: "center",
            gap: "10px",
            transition: "all 0.2s ease",
          }}
        >
          <FontAwesomeIcon
            icon={faUserCircle}
            size="lg"
            style={{
              transition: "all 0.2s ease",
            }}
          />
          <span
            style={{
              fontSize: "1.1rem",
              fontWeight: 500,
              transition: "all 0.2s ease",
            }}
          >
            {username || "Loading..."}
          </span>
        </div>
        <FontAwesomeIcon
          icon={faSignOutAlt}
          style={iconStyle}
          title="Logout"
          onClick={handleLogout}
          onMouseOver={(e) => {
            e.currentTarget.style.backgroundColor = "rgba(255, 255, 255, 0.1)";
            e.currentTarget.style.transform = "scale(1.05)";
          }}
          onMouseOut={(e) => {
            e.currentTarget.style.backgroundColor = "transparent";
            e.currentTarget.style.transform = "scale(1)";
          }}
        />
      </div>
    </header>
  );
};

export default Header;