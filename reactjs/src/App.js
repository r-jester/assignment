import Login from './Login';
import PageShow from './components/PageShow';

function App() {
  let isLogin=localStorage.getItem("user");

  return (
  <div className="App">
    {isLogin?<PageShow/>:<Login/>}
  </div>
  );
}

export default App;
