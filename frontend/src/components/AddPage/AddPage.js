import './AddPage.css';

import useAddPageHook from "./AddPageHook";

const AddPage = () => {

    const {
        data, setData,
        password, setPassword,
        addRendezvous,
        style,
        isOneShot, setIsOneShot,
        msg
    } = useAddPageHook();

    return (
        <div className="background">
            <div className='form'>
                <h3>Add a randezvous</h3>

                <label className="inputLabel" htmlFor="username">Data</label>
                <input className="inputBox" value={data} onChange={event => setData(event.target.value)} type="text"
                       placeholder="Data" id="data"/>

                <label className="inputLabel" htmlFor="password">Password</label>
                <input className="inputBox" value={password} onChange={event => setPassword(event.target.value)}
                       type="password" placeholder="Password" id="password"/>

                <input type="checkbox" id="vehicle1" name="vehicle1" value="Bike"
                       onClick={() => setIsOneShot(!isOneShot)}/>
                <label for="vehicle1"> One shot</label><br></br>

                <button className="button" onClick={() => addRendezvous()}>Add</button>
                <div className="social">
                    {/* <div className="go"><i className="fab fa-google"></i>  Google</div>
                <div className="fb"><i className="fab fa-facebook"></i>  Facebook</div> */}
                </div>
            </div>
            <h3 style={style}>{msg}</h3>
        </div>
    )
}

export default AddPage;