class FetcherClass {
  get = (uri, data = {}) => {
    data["method"] = "GET";
    return this._fetch(uri, data);
  };

  post = (uri, data = {}) => {
    data["method"] = "POST";
    return this._fetch(uri, data);
  };

  put = (uri, data = {}) => {
    data["method"] = "PUT";
    return this._fetch(uri, data);
  };

  delete = (uri, data = {}) => {
    data["method"] = "DELETE";
    return this._fetch(uri, data);
  };

  _fetch = (uri, data = {}) => {
    if (data["headers"] === undefined) {
      data["headers"] = new Headers({});
    }

    let token = sessionStorage.getItem("token");
    if (token) {
      if (!data["headers"].has("authorization")) {
        data["headers"].append("authorization", "Bearer " + token); 
      }
    }

    data["Content-Type"] = "application/json";
    return fetch("api/" + uri, data)
      .then(res => res.json())
      .catch(error => {
        console.log("Oops, and error: ", error);
      });
  };

  login = (username, password) => {
    const uri = "user/login";
    const credentials = {
      username: username,
      password: password
    };
    return this.post(uri, {
      body: JSON.stringify(credentials)
    })
      .then(token => token.data.token)
      .then(result => sessionStorage.setItem("token", result));
  };

  _renewToken = async () => {
    let token = sessionStorage.getItem("token");      
    if (token !== '') {
      let response = await this.post("user/renew-token");
      let returnToken = response.data.token;
      if (returnToken != 0) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  };

  isLogged = () => {
    return this._renewToken();
  };

  logout = () => {
    sessionStorage.removeItem('token');
    window.location.href = "/";
  }
}

const Fetcher = new FetcherClass();

export default Fetcher;
