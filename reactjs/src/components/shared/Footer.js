import React from "react";

const Footer = ({ username }) => {
  return (
    <footer
      style={{
        height: 60,
        backgroundColor: "#374151",
        color: "white",
        display: "flex",
        alignItems: "center",
        justifyContent: "center",
        fontSize: 14,
        userSelect: "none",
        flexShrink: 0,
        borderTop: "1px solid #4b5563",
        boxShadow: "0 -2px 10px rgba(0, 0, 0, 0.1)",
      }}
    >
      © 2025 Your Company — Logged in as{" "}
      <strong style={{ marginLeft: 5, color: "#60a5fa" }}>
        {username || "..."}
      </strong>
    </footer>
  );
};

export default Footer;