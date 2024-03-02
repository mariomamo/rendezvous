import './DataSpoiler.css';

function DataSpoiler({type, data}) {

    return (
        <div id='container'>
            <h3 className="titolo">Your data is</h3>

            <div className="body">
                {data}
            </div>
        </div>
    )
}

export default DataSpoiler;