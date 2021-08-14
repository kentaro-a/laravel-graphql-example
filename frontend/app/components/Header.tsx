import React, {useContext} from 'react';
import {Context} from 'pages/_app'
import css from 'styles/header.module.css'

const Header = () => {
	
	const {state, dispatch} = useContext(Context) 

	return (
		<nav className="sticky-top navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand">Nav</a>
			<div>
				{state.user &&
					<div style={{marginTop: '2em'}}>
						<p>login user</p>
						ID: {state.user.id}<br />
						Name: {state.user.name}<br />
						Mail: {state.user.mail}<br />
					</div>	
				}
			</div>
		</nav>
	)
}
export default Header

