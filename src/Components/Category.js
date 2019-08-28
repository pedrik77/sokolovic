import React, { Component } from "react";
import { withRouter } from "react-router-dom";
import Gallery from "react-grid-gallery";
import Fetcher from "./Fetcher";

class Category extends Component {
  constructor(props) {
    super(props);
    this.state = {
      photos: [],
      isLogged: ""
    };
  }
  handleUpload = e => {
    e.preventDefault();
    let photos = e.target.files;

    const gallery_id = this.props.match.params.category;
    let formdata = new FormData();
    formdata.append("gallery_id", gallery_id);
    for (let i = 0; i < photos.length; i++) {
      formdata.append("photo" + i, photos[i]);
    }

    Fetcher.post("photo", {
      body: formdata
    }).then(() => (window.location.href = `/${gallery_id}`));
    this.forceUpdate();
  };

  componentDidMount() {
    Fetcher.get(`gallery/${this.props.match.params.category}`).then(res =>
      this.setState({ photos: res.data })
    );

    Fetcher.isLogged().then(res => this.setState({ isLogged: res }));
  }

  onSelectImage(index, image) {
    var images = this.state.images.slice();
    var img = images[index];
    if (img.hasOwnProperty("isSelected")) img.isSelected = !img.isSelected;
    else img.isSelected = true;
    console.log(image);

    Fetcher.delete(`photo/${image.id}`).then(
      () => (window.location.href = `/${image.gallery_id}`)
    );
  }

  render() {
    let selectBtnTitle = document.querySelectorAll(
      ".ReactGridGallery_tile-icon-bar div:first-child"
    );
    selectBtnTitle.forEach(i => {
      i.setAttribute("title", "delete");
    });
    let addPhotos = "";
    let deletePhotos;
    if (this.state.isLogged === true) {
      addPhotos = (
        <div className="add-category-btn-div">
          <p className="add-category-btn-label">Pridať fotografie</p>
          <input onChange={this.handleUpload} className type="file" multiple />
        </div>
      );

      deletePhotos = (
        <button className="ui button" onClick={this.handleDeleteAll}>
          Delete All Photos
        </button>
      );
    }
    return (
      <div className="container">
        {addPhotos}
        {this.state.photos ? (
          this.state.isLogged ? (
            <Gallery
              images={this.state.photos}
              backdropClosesModal={true}
              onSelectImage={this.onSelectImage}
              enableImageSelection={true}
            />
          ) : (
            <Gallery
              images={this.state.photos}
              backdropClosesModal={true}
              enableImageSelection={false}
            />
          )
        ) : (
          ""
        )}
      </div>
    );
  }
}

export default withRouter(Category);
