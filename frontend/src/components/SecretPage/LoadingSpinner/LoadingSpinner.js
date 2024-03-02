import React from 'react';
import "./LoadingSpinner.css";

const LoadingSpinner = () => {

    return (
        <div className="spinner-container">
            <span className="spinner-loader"></span>
            <div className="spinner-loading-text">Loading ...</div>
        </div>
    )
}

export default LoadingSpinner;