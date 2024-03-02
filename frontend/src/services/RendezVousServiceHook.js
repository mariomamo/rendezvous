const _serverUrl = process.env.REACT_APP_RENDEZVOUS_SERVER;

const useRendezVousService = (serverUrl = _serverUrl) => {

    const addRendezVous = (password, data, isOneShot) => {
        return fetch(`${serverUrl}/put.php`, {
            body: JSON.stringify({"auth_code": password, "data": data, "one_shot": isOneShot}),
            method: "POST"
        })
    }

    const getRendezVous = (id, authCode) => {
        let body = JSON.stringify({"id": id, "auth_code": authCode});
        if (authCode === undefined) {
            body = JSON.stringify({"id": id});
        }

        return fetch(`${serverUrl}/get.php`, {
            method: "POST",
            headers: {'Content-Type':'application/json'},
            body: body
        })
    }

    const findRendezvous = (id) => {
        return fetch(`${serverUrl}/search.php/?id=${id}`, {method: "GET"});
    }

    return {addRendezVous, getRendezVous, findRendezvous}
}

export default useRendezVousService;