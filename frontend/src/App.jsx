import "./App.css";

import React, { StrictMode } from "react";
import { Home } from "./Components/Pages/Home/Home";
import { Post } from "./Components/Pages/Post/Post";

const App = () => {
  return (
    <>
      <Home />
      <Post />
    </>
  );
};

export default App;
