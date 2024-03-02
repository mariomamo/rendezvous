import './SecretPage.css';
import {useEffect} from 'react';
import useSecretPageHook from './SecretPageHook';
import DataSpoiler from './DataSpoiler/DataSpoiler';
import PasswordInput from './PasswodInput/PasswordInput';
import NotFound from '../NotFoundPage/NotFoundPage';
import LoadingSpinner from "./LoadingSpinner/LoadingSpinner";

function SecretPage() {

    const {
        id,
        notFound,
        auth,
        data,
        getRendezvous, findRendezvous,
        error
    } = useSecretPageHook();

    useEffect(() => {
        findRendezvous();
    }, [id]);

    return (
        <div>
            <div>
                {
                    (notFound && <NotFound/>) ||
                    (data && <DataSpoiler type="text" data={data}/>) ||
                    (auth === true && !data && <PasswordInput error={error} id={id} getRendezvous={getRendezvous}/>) ||
                    <LoadingSpinner/>
                }
            </div>
        </div>
    );
}

export default SecretPage;
