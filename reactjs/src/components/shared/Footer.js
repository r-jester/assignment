import React from 'react';

function Footer() {
    const currentYear = new Date().getFullYear(); // Get the current year

    const styles = {
        footer: {
            backgroundColor: '#282c34',
            color: 'white',
            textAlign: 'center',
            padding: '10px 20px',
            position: 'fixed', // Fixed position to keep it at the bottom
            left: '0',
            bottom: '0',
            width: '100%',
        },
        text: {
            margin: '0',
            fontSize: '0.9em',
        },
    };

    return (
        <footer style={styles.footer}>
            <p style={styles.text}>
                © {currentYear} All rights reserved. Built by <strong>Jester</strong>.
            </p>
        </footer>
    );
}

export default Footer;