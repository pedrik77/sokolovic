import React, { Component } from "react";
import Menu from "./Menu";
import Category from "./Category";
// import Fetcher from "./Fetcher";
import Index from "./Index";
import Fetcher from "./Fetcher";

class Home extends Component {
  render() {
    const category = this.props.match.params.category;
    let display;
    if (category) {
      display = <Category category={category} />;
    } else {
      display = <Index />;
    }
    let isLogged;
    Fetcher.isLogged().then(res => isLogged = res);
    console.log(isLogged);
    return (
      <div className="wrapper">
        {display}
        <Menu />
        {isLogged === true ? <button onClick={Fetcher.logout}>Logout</button> : ''}
        
      </div>
    );
  }
}

export default Home;
