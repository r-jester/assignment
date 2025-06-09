import React, { useState } from 'react';
import api from './services/api';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons';

function Login() {
  const [txtUser, setUser] = useState('');
  const [txtPass, setPass] = useState('');
  const [ErrorUser, setErrorUser] = useState('');
  const [ErrorPass, setErrorPass] = useState('');
  const [loginFail, setLoginFail] = useState('');
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);

  // Email validation regex
  const isValidEmail = (email) => {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  };

  function userLogin(e) {
    e.preventDefault();
    let error = 0;
    setLoginFail('');
    setIsLoading(true);

    // Validate username/email
    if (txtUser === '') {
      setErrorUser('Please input your username or email');
      error = 1;
    } else if (txtUser.includes('@') && !isValidEmail(txtUser)) {
      setErrorUser('Please enter a valid email address');
      error = 1;
    } else {
      setErrorUser('');
    }

    // Validate password
    if (txtPass === '') {
      setErrorPass('Please input your password');
      error = 1;
    } else if (txtPass.length < 3) {
      setErrorPass('Password must be at least 3 characters');
      error = 1;
    } else {
      setErrorPass('');
    }

    if (error === 0) {
      api.post('/users/login', {
        txtname: txtUser,
        txtpass: txtPass,
      })
        .then((response) => {
          console.log('Login response:', response.data);
          
          // Check if we have user data (successful login)
          if (response.data && response.data.user && response.status === 200) {
            localStorage.setItem('user', JSON.stringify(response.data.user));
            localStorage.setItem('isLoggedIn', 'true');
            
            // Show success message briefly before redirect
            setLoginFail('');
            
            // Redirect to home page
            setTimeout(() => {
              window.location.href = '/';
            }, 100);
          } else {
            setLoginFail('Login failed. Please try again.');
          }
        })
        .catch((error) => {
          console.error('Login error:', error.response ? error.response.data : error.message);
          
          if (error.response) {
            switch (error.response.status) {
              case 401:
                setLoginFail('Invalid username/email or password');
                break;
              case 422:
                setLoginFail('Please check your input and try again');
                break;
              case 429:
                setLoginFail('Too many login attempts. Please try again later');
                break;
              default:
                setLoginFail('An error occurred. Please try again');
            }
          } else {
            setLoginFail('Network error. Please check your connection');
          }
        })
        .finally(() => {
          setIsLoading(false);
        });
    } else {
      setIsLoading(false);
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
                      <p className="text-center small">Enter your username/email & password to login</p>
                    </div>

                    {loginFail && (
                      <div className="alert alert-danger" role="alert">
                        {loginFail}
                      </div>
                    )}

                    <form className="row g-3 needs-validation" onSubmit={userLogin} noValidate>
                      <div className="col-12">
                        <label htmlFor="yourUsername" className="form-label">Username or Email</label>
                        <div className="input-group has-validation">
                          <span className="input-group-text" id="inputGroupPrepend">@</span>
                          <input
                            type="text"
                            name="username"
                            className={`form-control ${ErrorUser ? 'is-invalid' : ''}`}
                            value={txtUser}
                            onChange={(e) => setUser(e.target.value)}
                            disabled={isLoading}
                            autoComplete="username"
                          />
                        </div>
                        {ErrorUser && (
                          <div className="invalid-feedback d-block">
                            {ErrorUser}
                          </div>
                        )}
                      </div>

                      <div className="col-12">
                        <label htmlFor="yourPassword" className="form-label">Password</label>
                        <div className="input-group">
                          <input
                            type={showPassword ? 'text' : 'password'}
                            name="password"
                            className={`form-control ${ErrorPass ? 'is-invalid' : ''}`}
                            value={txtPass}
                            onChange={(e) => setPass(e.target.value)}
                            disabled={isLoading}
                            autoComplete="current-password"
                          />
                          <button
                            type="button"
                            className="btn btn-outline-secondary"
                            onClick={() => setShowPassword(!showPassword)}
                            disabled={isLoading}
                          >
                            <FontAwesomeIcon icon={showPassword ? faEyeSlash : faEye} />
                          </button>
                        </div>
                        {ErrorPass && (
                          <div className="invalid-feedback d-block">
                            {ErrorPass}
                          </div>
                        )}
                      </div>

                      <div className="col-12">
                        <button 
                          className="btn btn-primary w-100" 
                          type="submit"
                          disabled={isLoading}
                        >
                          {isLoading ? (
                            <>
                              <span className="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                              Logging in...
                            </>
                          ) : (
                            'Login'
                          )}
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