import {useState} from 'react';
import useRendezVousService from "../../services/RendezVousServiceHook";

const useAddPageHook = () => {
    const [data, setData] = useState("");
    const [password, setPassword] = useState("");
    const [style, setStyle] = useState({display: 'none'});
    const [isOneShot, setIsOneShot] = useState(false);
    const [msg, setMsg] = useState("");
    const rendezVousService = useRendezVousService();

    const addRendezvous = () => {
        rendezVousService.addRendezVous(password, data, isOneShot)
            .then(response => {
                if (response.status === 200) {
                    setData("");
                    setPassword("");
                    setStyle({color: 'green'});
                    response.json().then(response => {
                        setMsg("Your rendezvous is available here https://rendezvous.altervista.org/#/secret/" + response["uuid"]);
                    });
                } else {
                    setStyle({color: 'red'});
                    setMsg("Error while adding rendezvous");
                }
            });
    }

    return {data, setData, password, setPassword, addRendezvous, style, isOneShot, setIsOneShot, msg};

}

export default useAddPageHook;