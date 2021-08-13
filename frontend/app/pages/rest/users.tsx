
import React from 'react'
import Head from 'next/head'
import UserAdd from 'components/UserAdd'
import {useContext, useState} from 'react';
import {Context} from 'pages/_app'
import ActionType from "context/ActionType"




const Users = (props) => {
	const [user_list, setUserList] = useState(props.user_list);
	const {state, dispatch} = useContext(Context) 

	const deleteItem = (user) => {
		let _user_list = user_list
		_user_list = _user_list.filter(v => v.id !== user.id)
		setUserList([..._user_list])
	}	
	
	const login = (user) => {
		dispatch({type: ActionType.LOGIN, payload: user})
	}

	const refreshUsers = async () => {
		const _user_list = await getUsers()
		setUserList([..._user_list])
	}

	return (
		<>
			<Head>
				<title>Users</title>
			</Head>
			<div style={{marginTop: '2em'}}>
				<UserAdd refreshUsers={refreshUsers}/>
			</div>

			{state.user &&
			<div style={{marginTop: '2em'}}>
				<p>login user</p>
				ID: {state.user.id}<br />
				Name: {state.user.name}<br />
				Mail: {state.user.mail}<br />
			</div>	
			}

			<div style={{marginTop: '2em'}}>
				{
					user_list.length > 0 && 
					(
						<table>
							<tbody>
								{user_list.map((u,i) => {
									return (
										<tr key={i}>
											<td>{u.id}</td>
											<td>{u.name}</td>
											<td>{u.mail}</td>
											<td><button onClick={() => login(u)}>login</button></td>
											<td><button onClick={() => deleteItem(u)}>del</button></td>
											
										</tr>
									)
								})}
							</tbody>
						</table>
					)
				}
			</div>
		</>
	)
}

const getUsers = async () => {
	let url = `${process.env.NEXT_PUBLIC_BACKEND_URL_OUT_OF_CONTAINER}/api/rest/user/list`
	if (typeof window === 'undefined') {
		url = `${process.env.NEXT_PUBLIC_BACKEND_BASE_URI}/api/rest/user/list`
	}
	const res = await fetch(url)
	const json = await res.json()
	return json.results

}


Users.getInitialProps = async () => {
	const user_list = await getUsers()
	return {user_list}
} 

export default Users 
