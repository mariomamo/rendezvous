import { useEffect, useState } from 'react';

const usePasswordInputHook = ({error}) => {
    const [authCode, setAuthCode] = useState("");
    const [errorStyle, setErrorStyle] = useState({display: 'none'});

    useEffect(() => {
        if (error) {
            setErrorStyle({color: 'red'});
        }
    }, [error]);
      

    return {authCode, setAuthCode, errorStyle};
    
}

export default usePasswordInputHook;