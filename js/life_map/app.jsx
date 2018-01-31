
ReactDOM.render(
      <Router>
          <Route path="/" component={App}>
              <Route path="/about" component={About} />
              <Route path="/about/:id" component={About} />
              <Route path="/inbox" component={Inbox} />
          </Route>
      </Router>,
    document.getElementById('myRouter'))