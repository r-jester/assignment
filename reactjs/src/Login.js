import React, { useState } from 'react';
import api from './services/api'; // Make sure this points to your axios instance

function Login() {
  const [txtUser, setUser] = useState('');
  const [txtPass, setPass] = useState('');
  const [ErrorUser, setErrorUser] = useState('');
  const [ErrorPass, setErrorPass] = useState('');
  const [loginFail, setLoginFail] = useState('');

  function userLogin() {
    let error = 0;
    setLoginFail('');

    if (txtUser === '') {
      setErrorUser('Please input your username or email');
      error = 1;
    } else {
      setErrorUser('');
    }

    if (txtPass === '') {
      setErrorPass('Please input your password');
      error = 1;
    } else {
      setErrorPass('');
    }

    if (error === 0) {
      api.post('/users/login', {
        txtname: txtUser,
        txtemail: txtUser, // Assuming name and email are same input
        txtpass: txtPass,
      })
        .then((response) => {
          localStorage.setItem('user', JSON.stringify(response.data.user));
          window.location.href = '/';
        })
        .catch(() => {
          setLoginFail('Invalid Username or Password');
        });
    }
  }

  return (
    <main>
      <div className="container">
        <section className="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
          <div className="container">
            <div className="row justify-content-center">
              <div className="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                <div className="card mb-3">
                  <div className="card-body">
                    <div className="pt-4 pb-2">
                      <h5 className="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                      <p className="text-center small">Enter your name/email & password to login</p>
                    </div>

                    <p style={{ color: 'red' }}>{loginFail}</p>

                    <form className="row g-3 needs-validation" noValidate>
                      <div className="col-12">
                        <label htmlFor="yourUsername" className="form-label">Username or Email</label>
                        <div className="input-group has-validation">
                          <span className="input-group-text" id="inputGroupPrepend">@</span>
                          <input
                            type="text"
                            name="username"
                            className="form-control"
                            value={txtUser}
                            onChange={(e) => setUser(e.target.value)}
                          />
                        </div>
                        <p style={{ color: 'red' }}>{ErrorUser}</p>
                      </div>

                      <div className="col-12">
                        <label htmlFor="yourPassword" className="form-label">Password</label>
                        <input
                          type="password"
                          name="password"
                          className="form-control"
                          value={txtPass}
                          onChange={(e) => setPass(e.target.value)}
                        />
                        <p style={{ color: 'red' }}>{ErrorPass}</p>
                      </div>

                      <div className="col-12">
                        <button
                          className="btn btn-primary w-100"
                          type="button"
                          onClick={userLogin}
                        >
                          Login
                        </button>
                      </div>

                      <div className="col-12">
                        <p className="small mb-0">
                          Don't have an account? <a href="/register">Create an account</a>
                        </p>
                      </div>
                    </form>

                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </div>
    </main>
  );
}

export default Login;
