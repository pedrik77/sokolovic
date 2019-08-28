import React, { Component } from "react";
import Fetcher from "./Fetcher";

class Menu extends Component {
  constructor(props) {
    super(props);
    this.state = {
      items: [],
      logged: false
    };
  }

  componentDidMount() {
    Fetcher.get("gallery").then(result =>
      this.setState({ items: result.data })
    );

    Fetcher.isLogged().then(res =>
      this.setState({ logged: res })
    );
  }

  menuItemToCapitalize = str => {
    return (
      str.slice(0, 1).toUpperCase() + str.slice(1, str.length).toLowerCase()
    );
  };

  render() {
    const items = this.state.items;
    return (
      <nav>
        <div>
          <a href="/">Home</a>
          {this.state.logged === true ? (
            <img className="edit-button" alt="edit button" src="./img/edit.png"/>
          ) : (
            ""
          )}
        </div>
        {items.map(item => {
          return (
            <div>
              <a key={item.id} href="/food">{this.menuItemToCapitalize(item.name)}</a>
            </div>
          );
        })}
      </nav>
    );
  }
}

export default Menu;
