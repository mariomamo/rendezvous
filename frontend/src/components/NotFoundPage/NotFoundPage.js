import './NotfoundPage.css';

const NotFound = () => {
    return (
        <div id="container">
            <div className="error_box">
                <div className="number">404</div>
                <div className="text">Data not found</div>
                <div className="text">Page or data may not exists</div>
            </div>
        </div>
    )
}

export default NotFound;