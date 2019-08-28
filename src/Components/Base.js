import React, { Component } from "react";
import { BrowserRouter, Route, Switch, NavLink } from "react-router-dom";
import "../App.scss";
import Category from "./Category";
import Login from "./Login";
import Index from "./Index";
import Fetcher from "./Fetcher";
import Title from "./Title";

class Base extends Component {
  constructor(props) {
    super(props);
    this.state = {
      items: [],
      logged: false,
      tempItem: ""
    };

    this.handleClick = this.handleClick.bind(this);
  }
  componentDidMount() {
    Fetcher.get("gallery").then(result =>
      this.setState({ items: result.data })
    );

    Fetcher.isLogged().then(res => this.setState({ logged: res }));
  }

  menuItemToCapitalize = str => {
    return (
      str.slice(0, 1).toUpperCase() + str.slice(1, str.length).toLowerCase()
    );
  };

  handleClick = e => {
    let clickedId = e.target.id;
    let Navlink = document.getElementById("navNavlink" + clickedId);
    let form = document.getElementById("form" + clickedId);
    let deleteCat = document.querySelector(`.delete-button${clickedId}`);
    const saveBtn = document.querySelector(".save-button" + clickedId);
    const editBtn = document.querySelector(".edit-button" + clickedId);

    deleteCat.style.display = "none";
    Navlink.style.display = "none";
    form.style.display = "block";
    saveBtn.style.display = "inline";
    editBtn.style.display = "none";
  };

  addGallery = e => {
    e.target.style.display = "none";
    const addCatForm = document.getElementById("addCatForm");
    addCatForm.style.display = "block";
    const addCatSaveBtn = document.getElementById("addCatSaveBtn");
    addCatSaveBtn.style.display = "block";
  };

  handleUpdateCategory = e => {
    //get category ID
    let categoryId = e.target.id;
    //get category input
    let categoryNameInput = document.getElementById(`category${categoryId}`)
      .value;
    let data = {
      name: categoryNameInput
    };
    if (categoryNameInput && categoryId) {
      Fetcher.put(`gallery/${categoryId}`, {
        body: JSON.stringify(data)
      }).then(() => (window.location.href = "/"));
    }
  };

  handleCreateCategory = () => {
    const createCategoryInput = document.getElementById("addCategoryInput")
      .value;
    if (createCategoryInput) {
      Fetcher.post("gallery", {
        body: JSON.stringify({
          name: createCategoryInput
        })
      }).then(() => (window.location.href = "/"));
    }
  };

  handleDeleteCategory = e => {
    const catId = e.target.id;
    const areYouSure = window.confirm("Are you sure to delete this category ?");
    if (areYouSure) {
      Fetcher.delete(`gallery/${catId}`).then(
        () => (window.location.href = "/")
      );
    }
  };

  render() {
    const items = this.state.items;
    let logoutBtn;
    let addCategoryBtn;
    if (this.state.logged) {
      logoutBtn = (
        <div
          style={{ border: "none", paddingRight: "3px" }}
          className="button-logout"
        >
          <button className="ui button" onClick={Fetcher.logout}>
            Logout
          </button>
        </div>
      );

      addCategoryBtn = (
        <div>
          <form id="addCatForm" style={{ display: "none" }}>
            <div className="ui input">
              <input
                id="addCategoryInput"
                type="text"
                defaultValue=""
                style={{ maxWidth: "100px" }}
              />
            </div>
          </form>
          <img
            id="addCatSaveBtn"
            onClick={this.handleCreateCategory}
            className="save-button"
            style={{ maxWidth: "20px", display: "none", marginLeft: "5px" }}
            alt="Save button"
            src="./img/checked.png"
          />
          <span className="add-category" onClick={this.addGallery}>
            +
          </span>
        </div>
      );
    }
    return (
      <div className="wrapper">
        <BrowserRouter>
          <nav>
            {logoutBtn}
            <div>
              <NavLink key="Home" to="/">
                Home
              </NavLink>
            </div>
            {items &&
              items.map(item => {
                return (
                  <div key={item.id}>
                    <a key={item.id} href={`/${item.id}`}>
                      {this.menuItemToCapitalize(item.name)}
                    </a>
                    <form
                      action={`gallery/${item.id}`}
                      style={{ display: "none" }}
                      id={`form${item.id}`}
                    >
                      <div className="ui input">
                        <input
                          id={`category${item.id}`}
                          name="categoryName"
                          type="text"
                          defaultValue={item.name}
                          style={{ maxWidth: "100px" }}
                        />
                      </div>
                    </form>
                    {this.state.logged === true ? (
                      <span onClick={this.handleClick}>
                        <img
                          className={`save-button save-button${item.id}`}
                          id={item.id}
                          alt="Save button"
                          src="./img/checked.png"
                          onClick={this.handleUpdateCategory}
                        />
                        <img
                          className={`edit-button edit-button${item.id}`}
                          id={item.id}
                          alt="Edit button"
                          src="./img/edit.png"
                        />
                        <img
                          id={item.id}
                          onClick={this.handleDeleteCategory}
                          className={`delete-button delete-button${item.id}`}
                          alt="Delete Category"
                          src="./img/delete.png"
                        />
                      </span>
                    ) : (
                      ""
                    )}
                  </div>
                );
              })}
            {addCategoryBtn}
          </nav>
          <Switch>
            <Route key="Home" exact path="/" component={Index} />
            <Route key="login" exact path="/login" component={Login} />
            <Route key="category" path="/:category" component={Category} />
          </Switch>
        </BrowserRouter>
        <Title />
      </div>
    );
  }
}

export default Base;
