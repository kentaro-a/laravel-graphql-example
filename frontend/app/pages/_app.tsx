import 'bootstrap/dist/css/bootstrap.min.css';
import '../styles/globals.css'
import React, { useReducer, useContext, createContext } from 'react';
import ActionType from "context/ActionType"
import initial_state from "context/initial_state"
import reducer from "context/reducer"
import Layout from "components/Layout"

const Context = createContext()
export {Context}

function MyApp({ Component, pageProps }) {
	const [state, dispatch] = useReducer(reducer, initial_state)

	return (
		<Context.Provider value={{state, dispatch}}>
			<Layout>
				<Component {...pageProps} />
			</Layout>
		</Context.Provider>
	)
}






export default MyApp
