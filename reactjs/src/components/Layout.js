
import React, { useState, useEffect } from "react"; // Combine imports
import { Outlet, useNavigate } from "react-router-dom"; // Import useNavigate
import Header from "./shared/Header";
import Sidebar from "./shared/Sidebar";
import Footer from "./shared/Footer";

function Layout() {
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);

  const toggleSidebar = () => {
    setIsSidebarCollapsed(!isSidebarCollapsed);
  };

  return (
    <div style={styles.layout}>
      {/* Header */}
      <header style={styles.header}>
        <Header
          toggleSidebar={toggleSidebar}
          isSidebarCollapsed={isSidebarCollapsed}
        />
      </header>

      {/* Main Content Area */}
      <div style={styles.mainContent}>
        {/* Sidebar */}
        <aside
          style={{
            ...styles.sidebar,
            width: isSidebarCollapsed ? "0" : "250px",
            overflow: isSidebarCollapsed ? "hidden" : "auto",
          }}
        >
          <Sidebar isCollapsed={isSidebarCollapsed} />
        </aside>

        {/* Main Content */}
        <main
          style={{
            ...styles.content,
            marginLeft: isSidebarCollapsed ? "0" : "250px",
          }}
        >
          <Outlet /> {/* Nested routes will be rendered here */}
        </main>
      </div>

      {/* Footer */}
      <footer style={styles.footer}>
        <Footer />
      </footer>
    </div>
  );
}

export default Layout;

const styles = {
  layout: {
    display: "flex",
    flexDirection: "column",
    minHeight: "100vh",
  },
  header: {
    position: "fixed",
    top: 0,
    left: 0,
    right: 0,
    zIndex: 1000,
    backgroundColor: "#282c34",
    color: "white",
    padding: "5px 20px",
  },
  mainContent: {
    display: "flex",
    flex: 1,
    marginTop: "60px", // Adjust based on header height
    marginBottom: "60px", // Adjust based on footer height
  },
  sidebar: {
    position: "fixed",
    top: "60px", // Adjust based on header height
    left: 0,
    bottom: "60px", // Adjust based on footer height
    backgroundColor: "#2c3e50",
    color: "white",
    padding: "20px",
    transition: "width 0.3s ease, overflow 0.3s ease",
  },
  content: {
    flex: 1,
    padding: "20px",
    overflowY: "auto",
    transition: "margin-left 0.3s ease",
  },
  footer: {
    position: "fixed",
    bottom: 0,
    left: 0,
    right: 0,
    zIndex: 1000,
    minHeight: "70px",
    backgroundColor: "#282c34",
    color: "white",
    padding: "10px 20px",
    textAlign: "center",
  },
};
