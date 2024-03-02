import {useParams} from 'react-router-dom';
import {useState} from 'react';
import useRendezVousService from "../../services/RendezVousServiceHook";

const useSecretPageHook = () => {
    const {id} = useParams();
    const [notFound, setNotFound] = useState(false);
    const [auth, setAuth] = useState(false);
    const [data, setData] = useState("");
    const [error, setError] = useState(false);
    const rendezVousService = useRendezVousService();

    const getRendezvous = (id, authCode) => {
        rendezVousService.getRendezVous(id, authCode).then(response => {
            if (response.status === 200) {
                response.json()
                    .then(response => {
                        setData(response["data"]);
                    });
            } else {
                setError(true);
            }
        });
    }

    const findRendezvous = () => {
        rendezVousService.findRendezvous(id)
            .then(response => {
                if (response.status === 200) {
                    setNotFound(false);
                    response.json()
                        .then(response => {
                            setAuth(response["auth"]);
                            if (!response["auth"]) {
                                getRendezvous(id);
                            }
                        });
                } else {
                    setNotFound(true);
                }
            })
    }

    return {id, notFound, auth, data, getRendezvous, findRendezvous, error};

}

export default useSecretPageHook;