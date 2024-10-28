import React, { useEffect, useState } from "react";
import axios from "axios";
import { htmlToText } from "html-to-text";
import "./post.css";

export const Post = () => {
  const [posts, setPosts] = useState([]);

  useEffect(() => {
    const url = "http://localhost/headeless/server/wp-json/wp/v2/posts";
    axios.get(url).then((res) => {
      setPosts(res.data);
    });
  }, []);

  const convertToPlainText = (htmlContent) => {
    // Convert HTML to plain text, remove hyphens, image links, and extra characters
    let plainText = htmlToText(htmlContent, {
      wordwrap: 130,
      selectors: [
        { selector: "a", format: "inline" }, // Remove <a> tags, keep link text
        { selector: "p", format: "inline" }, // Remove <p> tags, keep paragraph text
        { selector: "span", format: "inline" }, // Remove <span> tags, keep span tex
      ],
    });

    // Further clean up text: remove hyphens and double hyphens
    return plainText.replace(/-/g, "").replace(/--/g, "");
  };

  return (
    <div className="container">
      <ul>
        {posts.map((post) => (
          <li key={post.id}>
            <div className="gradient-cards">
              <div className="card">
                <div className="container-card bg-green-box">
                  <p className="card-title">{post.title.rendered}</p>
                  <p className="card-description">
                    {convertToPlainText(post.content.rendered)}
                  </p>
                </div>
              </div>
            </div>
          </li>
        ))}
      </ul>
    </div>
  );
};
