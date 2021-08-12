import React from 'react'
import {ApolloClient, gql, InMemoryCache} from '@apollo/client'


type State = {
	name: string,
	mail: string,
	password: string,
}

class UserAdd extends React.Component<{}, State> {
	constructor(props) {
		super(props)

		this.state = {
			name: "",
			mail: "",
			password: "", 
		} 
		this.handleChange = this.handleChange.bind(this)
	}

	handleChange(e) {
		this.setState({...this.state, [e.target.name]: e.target.value})
	}


	render() {
		return (
			<div>
				<div>
					Name: 
					<input
						type="text"
						name="name"
						onChange={this.handleChange}
						value={this.state.name}
						required
					/>
				</div>
				<div>
					Mail: 
					<input
						type="text"
						name="mail"
						onChange={this.handleChange}
						value={this.state.mail}
						required
					/>
				</div>
				<div>
					Password: 
					<input
						type="password"
						name="password"
						onChange={this.handleChange}
						value={this.state.password}
						required
					/>
				</div>
			</div>
		) 
	}
}
export default UserAdd
