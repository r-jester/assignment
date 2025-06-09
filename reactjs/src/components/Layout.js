import React, { useEffect, useState } from "react";
import { Outlet } from "react-router-dom";
import Sidebar from "./shared/Sidebar";
import Header from "./shared/Header";
import Footer from "./shared/Footer";
import api from "../services/api";

const Layout = () => {
  const [sidebarOpen, setSidebarOpen] = useState(true);
  const [username, setUsername] = useState("");

  useEffect(() => {
    const user = JSON.parse(localStorage.getItem("user"));
    if (user && user.id) {
      api
        .get(`/users/${user.id}`)
        .then((res) => {
          setUsername(res.data.username || user.username || "User");
        })
        .catch((error) => {
          console.error("Error fetching user data:", error);
          setUsername(user.username || "User");
        });
    } else {
      setUsername("Guest");
    }
  }, []);

  return (
    <div style={{ display: "flex", minHeight: "100vh" }}>
      <Sidebar isCollapsed={!sidebarOpen} username={username} />

      <div
        style={{
          marginLeft: sidebarOpen ? 250 : 0,
          flexGrow: 1,
          display: "flex",
          flexDirection: "column",
          transition: "margin-left 0.4s cubic-bezier(0.25, 0.8, 0.25, 1)",
          backgroundColor: "#f3f4f6",
          minHeight: "100vh",
          overflowX: "hidden",
        }}
      >
        <Header
          toggleSidebar={() => setSidebarOpen(!sidebarOpen)}
          username={username}
        />

        <main
          style={{
            flexGrow: 1,
            padding: 25,
            overflowY: "auto",
            minHeight: 0,
          }}
        >
          <Outlet />
        </main>

        <Footer username={username} />
      </div>
    </div>
  );
};

export default Layout;