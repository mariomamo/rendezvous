import './PasswordInput.css';
import usePasswordInputHook from "./PasswordInputHook";

function PasswordInput({error, id, getRendezvous}) {

    const {authCode, setAuthCode, errorStyle} = usePasswordInputHook({error});

    return (
        <div className="background">
            <div className='form'>
                <h3>Login Here</h3>

                <label className="inputLabel" htmlFor="username">ID</label>
                <input className="inputBox" type="text" value={id} id="username" readOnly={true} />

                <label className="inputLabel" htmlFor="password">Password</label>
                <input className="inputBox" onChange={event => setAuthCode(event.target.value)} type="password" placeholder="Password" id="password" />

                <button className="button" onClick={() => getRendezvous(id, authCode)}>Go to resource</button>
                <div className="social">
                </div>
            </div>
            <h3 style={errorStyle}>Error, try again</h3>
        </div>
    )
}

export default PasswordInput;