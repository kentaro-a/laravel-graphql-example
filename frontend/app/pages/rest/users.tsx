
import React from 'react'
import Head from 'next/head'
import UserAdd from 'components/UserAdd'
import fetch from 'isomorphic-unfetch'




class Users extends React.Component {

	constructor(props) {
		super(props)
		this.state = {user_list: props.user_list}
		this.deleteItem = this.deleteItem.bind(this)
	}

	static async getInitialProps({ Component, router, ctx }) {
		try {
			const res = await fetch(`${process.env.NEXT_PUBLIC_BACKEND_BASE_URI}/api/rest/user/list`)
			const json = await res.json()
			return {user_list: json.results} 

		} catch (e) {
			console.log(e)
		}
	}


	deleteItem() {
		let user_list = this.state.user_list
		user_list.shift()
		this.setState({user_list: user_list})
	}	
	
	debug() {
	}

	render() {
		return (
			<>
				<Head>
					<title>XXXX</title>
				</Head>
				<div style={{marginTop: '2em'}}>
					<UserAdd />
				</div>

				<div style={{marginTop: '2em'}}>
					<p>register user info</p>
					Name: aaa<br />
					Mail: aaa<br />
					Password: aaa<br />
				</div>	

				<div style={{marginTop: '2em'}}>
					<button onClick={this.debug}>debug</button>
				</div>	

				<div style={{marginTop: '2em'}}>
					<button onClick={this.deleteItem}>del</button>
					{
						this.state.user_list.length > 0 ??
						(

							<table>
							{this.state.user_list.map((u,i) => {
								return (
									<tr key={i}>
										<td>{u.id}</td>
										<td>{u.name}</td>
									</tr>
								)
							})}
							</table>
						)
					}
				</div>
			</>
		)
	}
}

export default Users 
