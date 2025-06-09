import React, { useState } from "react";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Layout from "./Layout";
import Dashboard1 from "./pages/Dashboard1";
import AddStaff from "./staffs/AddStaff";
import ListStaff from "./staffs/ListStaff";
import AddProduct from "./products/AddProduct";
import ListProduct from "./products/ListProduct";
import AddCustomer from "./customers/AddCustomer";
import ListCustomer from "./customers/ListCustomer";
import AddSale from "./sales/AddSale";
import ListSale from "./sales/ListSale";
import AddExpense from "./expenses/AddExpense";
import ListExpense from "./expenses/ListExpense";

function PageShow() {
  // State for staff management
  const [staffList, setStaffList] = useState([]);
  const addStaff = (newStaff) => {
    setStaffList([...staffList, newStaff]);
  };

  // State for product management
  const [productList, setProductList] = useState([]);
  const addProduct = (newProduct) => {
    setProductList([...productList, newProduct]);
  };

  // State for customer management
  const [customerList, setCustomerList] = useState([]);
  const addCustomer = (newCustomer) => {
    setCustomerList([...customerList, newCustomer]);
  };

  // State for sale management
  const [saleList, setSaleList] = useState([]);
  const addSale = (newSale) => {
    setSaleList([...saleList, newSale]);
  };

  // State for expense management
  const [expenseList, setExpenseList] = useState([]);
  const addExpense = (newExpense) => {
    setExpenseList([...expenseList, newExpense]);
  };

  return (
    <BrowserRouter>
      <Routes>
        {/* Wrap all routes with Layout */}
        <Route path="/" element={<Layout />}>
          {/* Dashboard route */}
          {/* <Route index element={<Dashboard1 />} /> Default route */}
          <Route index element={<Dashboard1 />} />{" "}
          {/* Updated to /dashboard */}
          {/* Staff route */}
          <Route path="add-staff" element={<AddStaff addStaff={addStaff} />} />
          <Route
            path="list-staff"
            element={<ListStaff staffList={staffList} />}
          />
          {/* Product Routes */}
          <Route
            path="add-product"
            element={<AddProduct addProduct={addProduct} />}
          />
          <Route
            path="list-product"
            element={<ListProduct productList={productList} />}
          />
          {/* Customer Routes */}
          <Route
            path="add-customer"
            element={<AddCustomer addCustomer={addCustomer} />}
          />
          <Route
            path="list-customer"
            element={<ListCustomer customerList={customerList} />}
          />
          {/* Sale Routes */}
          <Route path="add-sale" element={<AddSale addSale={addSale} />} />
          <Route path="list-sale" element={<ListSale saleList={saleList} />} />
          {/* Expense Routes */}
          <Route
            path="add-expense"
            element={<AddExpense addExpense={addExpense} />}
          />
          <Route
            path="list-expense"
            element={<ListExpense expenseList={expenseList} />}
          />
        </Route>
      </Routes>
    </BrowserRouter>
  );
}

export default PageShow;