import React from 'react';

class SpinnerLoader extends React.Component {
    constructor(props) {
        super();
    }

    render() {
        return (
            // <i style="width:100px" style={this.props.loaderSize} className="fa fa-spinner fa-spin"></i>
            <i  className="fa fa-spinner fa-spin"></i>
        )
    }
}

SpinnerLoader.defaultProps = {
    loaderSize: {width:"10px",height:"10px"},
}

export default SpinnerLoader;