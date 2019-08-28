import React, { Component } from "react";
import Fetcher from "./Fetcher";

class Upload extends Component {
  constructor(){
    super();
    this.state = {
      logged: ''
    }
  }
  handleUpload = e => {
    e.preventDefault();
    let photos = e.target.photos.files;
    console.log(photos);

    const gallery_id = e.target.gallery_id.value;
    let formdata = new FormData();
    for (let i = 0; i < photos.length; i++) {
      formdata.append("photos" + i, photos[i]);
    }

    formdata.append("gallery_id", gallery_id);

    Fetcher.post("photo", {
      body: formdata
    }).then(res => console.log(res));
    this.forceUpdate();
  };
  
  
  componentWillMount() {    
    Fetcher.isLogged(res => res.json()).then(res => this.setState({logged: res}));
  }
  
  render() {
    console.log('This', this.state.logged);

    if (this.state.logged === false) {
      window.location.href = "/";
    } else {
      return (
        <form onSubmit={this.handleUpload} style={{ padding: "10px" }}>
          <input
            style={{ margin: "10px 10px 10px 0" }}
            className="ui button"
            type="file"
            name="photos"
            multiple
          />
          <label for="gallery_id">Číslo kategórie:</label>
          <input
            type="text"
            placeholder="Sem zadajte číslo kategórie"
            name="gallery_id"
          />
          <button className="ui primary button" type="submit">
            Send photos
          </button>
        </form>
      );
    }
  }
}

export default Upload;
