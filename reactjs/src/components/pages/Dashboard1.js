import React from 'react';
import { Chart as ChartJS, ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title } from 'chart.js';
import { Pie, Bar } from 'react-chartjs-2';

// Register Chart.js components
ChartJS.register(ArcElement, Tooltip, Legend, CategoryScale, LinearScale, BarElement, Title);

function Dashboard1() {
    // Data for the cards
    const cardData = [
        { title: 'Total Sales', value: '$12,345', color: '#3b82f6' }, // Blue
        { title: 'Total Orders', value: '1,234', color: '#10b981' }, // Green
        { title: 'Total Customers', value: '567', color: '#f59e0b' }, // Yellow
        { title: 'Total Revenue', value: '$98,765', color: '#8b5cf6' }, // Purple
        { title: 'Total Products', value: '789', color: '#ef4444' }, // Red
    ];

    // Data for the popular products table
    const popularProducts = [
        { id: 1, name: 'iPhone 14', sales: 120 },
        { id: 2, name: 'Samsung Galaxy S22', sales: 95 },
        { id: 3, name: 'Vivo V25', sales: 80 },
        { id: 4, name: 'Oppo Reno 7', sales: 75 },
        { id: 5, name: 'Xiaomi 12', sales: 60 },
    ];

    // Data for the pie chart (product quantities)
    const pieChartData = {
        labels: ['iPhone', 'Samsung', 'Vivo', 'Oppo', 'Xiaomi'],
        datasets: [
            {
                label: 'Quantity',
                data: [25, 35, 20, 10, 10], // Example quantities
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'],
            },
        ],
    };

    // Data for the column chart (sales trends)
    const columnChartData = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [
            {
                label: 'Sales',
                data: [65, 59, 80, 81, 56, 55], // Example sales data
                backgroundColor: '#36A2EB',
            },
        ],
    };

    // Inline styles for Dashboard1
    const styles = {
        dashboardContainer: {
            padding: '20px',
        },
        cardsGrid: {
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
            gap: '20px',
            marginBottom: '20px',
        },
        card: {
            padding: '20px',
            borderRadius: '10px',
            color: 'white',
            textAlign: 'center',
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
        },
        popularProducts: {
            marginBottom: '20px',
        },
        popularProductsHeading: {
            fontSize: '1.5rem',
            marginBottom: '10px',
        },
        table: {
            width: '100%',
            borderCollapse: 'collapse',
            backgroundColor: 'white',
            border: '1px solid #e5e7eb',
        },
        tableHeader: {
            backgroundColor: '#f3f4f6',
            padding: '10px',
            textAlign: 'center',
            borderBottom: '1px solid #e5e7eb',
        },
        tableCell: {
            padding: '10px',
            textAlign: 'center',
            borderBottom: '1px solid #e5e7eb',
        },
        chartsGrid: {
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(300px, 1fr))',
            gap: '20px',
        },
        chartContainer: {
            backgroundColor: 'white',
            padding: '20px',
            borderRadius: '10px',
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)',
        },
        chartHeading: {
            fontSize: '1.5rem',
            marginBottom: '10px',
        },
    };

    return (
        <div style={styles.dashboardContainer}>
            {/* Cards Section */}
            <div style={styles.cardsGrid}>
                {cardData.slice(0, 3).map((card, index) => (
                    <div key={index} style={{ ...styles.card, backgroundColor: card.color }}>
                        <h3>{card.title}</h3>
                        <p>{card.value}</p>
                    </div>
                ))}
            </div>
            <div style={styles.cardsGrid}>
                {cardData.slice(3).map((card, index) => (
                    <div key={index} style={{ ...styles.card, backgroundColor: card.color }}>
                        <h3>{card.title}</h3>
                        <p>{card.value}</p>
                    </div>
                ))}
            </div>

            {/* Popular Products Table */}
            <div style={styles.popularProducts}>
                <h2 style={styles.popularProductsHeading}>Popular Products</h2>
                <table style={styles.table}>
                    <thead>
                        <tr>
                            <th style={styles.tableHeader}>ID</th>
                            <th style={styles.tableHeader}>Product Name</th>
                            <th style={styles.tableHeader}>Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        {popularProducts.map((product) => (
                            <tr key={product.id}>
                                <td style={styles.tableCell}>{product.id}</td>
                                <td style={styles.tableCell}>{product.name}</td>
                                <td style={styles.tableCell}>{product.sales}</td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>

            {/* Charts Section */}
            <div style={styles.chartsGrid}>
                {/* Pie Chart */}
                <div style={styles.chartContainer}>
                    <h2 style={styles.chartHeading}>Product Quantity Analysis</h2>
                    <Pie data={pieChartData} />
                </div>

                {/* Column Chart */}
                <div style={styles.chartContainer}>
                    <h2 style={styles.chartHeading}>Sales Trend Analysis</h2>
                    <Bar data={columnChartData} />
                </div>
            </div>
        </div>
    );
}

export default Dashboard1;