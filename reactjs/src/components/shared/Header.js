import React from 'react';

function Header() {
    const styles = {
        header: {
            display: 'flex',
            justifyContent: 'space-between',
            alignItems: 'center',
            padding: '5px 20px',
            backgroundColor: '#282c34',
            color: 'white',
        },
        headerLeft: {
            display: 'flex',
            alignItems: 'center',
        },
        headerRight: {
            display: 'flex',
            alignItems: 'center',
        },
        appLogo: {
            height: '40px', // Adjust the height of the logo
            width: 'auto', // Maintain aspect ratio
        },
        icons: {
            display: 'flex',
            gap: '10px', // Adds space between icons
        },
        iconButton: {
            background: 'none',
            border: 'none',
            color: 'white',
            fontSize: '1.5em',
            cursor: 'pointer',
            padding: '5px 10px', // Add padding for better spacing
            borderRadius: '5px', // Rounded corners
            transition: 'background-color 0.3s ease', // Smooth hover effect
        },
        iconButtonHover: {
            backgroundColor: '#61dafb', // Background color on hover
        },
        logoutButton: {
            background: '#ff4d4d', // Red background for logout button
            color: 'white',
            border: 'none',
            padding: '8px 16px', // More padding for a button-like appearance
            borderRadius: '5px', // Rounded corners
            cursor: 'pointer',
            fontSize: '1em',
            fontWeight: 'bold',
            transition: 'background-color 0.3s ease', // Smooth hover effect
        },
        logoutButtonHover: {
            backgroundColor: '#ff1a1a', // Darker red on hover
        },
    };

    function logout() {
        localStorage.clear();
        window.location.href = "/";
    }

    return (
        <header style={styles.header}>
            <div style={styles.headerLeft}>
                {/* Replace "React App" with an image */}
                <img
                    src="./Rakyruu.png" // Path to your logo image
                    alt="App Logo"
                    style={styles.appLogo}
                />
                <h1 style={{display: 'flex', alignItems: 'center'}}>Jester</h1>
            </div>
            <div style={styles.headerRight}>
                <div style={styles.icons}>
                    {/* Logout Button */}
                    <button
                        onClick={logout}
                        style={styles.logoutButton}
                        onMouseEnter={(e) => (e.target.style.backgroundColor = styles.logoutButtonHover.backgroundColor)}
                        onMouseLeave={(e) => (e.target.style.backgroundColor = styles.logoutButton.backgroundColor)}
                    >
                        Sign Out
                    </button>

                    {/* Notification Icon */}
                    <button
                        style={styles.iconButton}
                        onMouseEnter={(e) => (e.target.style.backgroundColor = styles.iconButtonHover.backgroundColor)}
                        onMouseLeave={(e) => (e.target.style.backgroundColor = 'transparent')}
                    >
                        🔔
                    </button>

                    {/* Settings Icon */}
                    <button
                        style={styles.iconButton}
                        onMouseEnter={(e) => (e.target.style.backgroundColor = styles.iconButtonHover.backgroundColor)}
                        onMouseLeave={(e) => (e.target.style.backgroundColor = 'transparent')}
                    >
                        ⚙️
                    </button>

                    {/* Profile Icon */}
                    <button
                        style={styles.iconButton}
                        onMouseEnter={(e) => (e.target.style.backgroundColor = styles.iconButtonHover.backgroundColor)}
                        onMouseLeave={(e) => (e.target.style.backgroundColor = 'transparent')}
                    >
                        👤
                    </button>
                </div>
            </div>
        </header>
    );
}

export default Header;