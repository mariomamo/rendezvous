import {HashRouter as BrowserRouter, Route, Routes} from "react-router-dom";
import './App.css';
import SecretPage from './components/SecretPage/SecretPage';
import AddPage from './components/AddPage/AddPage';
import NotFound from './components/NotFoundPage/NotFoundPage';

function App() {
    return (
        <BrowserRouter>
            <Routes>
                {/* <Route path="/home" element={<HomePage />} /> */}
                <Route path="/secret/:id" element={<SecretPage/>}/>
                <Route path="/add" element={<AddPage/>}/>
                <Route path='*' element={<NotFound/>}/>
            </Routes>
        </BrowserRouter>
    );
}

export default App;
