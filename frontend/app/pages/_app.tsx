import '../styles/globals.css'
import React, { useReducer, useContext, createContext } from 'react';
import ActionType from "context/ActionType"
import initial_state from "context/initial_state"
import reducer from "context/reducer"

const Context = createContext()
export {Context}

function MyApp({ Component, pageProps }) {
	const [state, dispatch] = useReducer(reducer, initial_state)

	return (
		<Context.Provider value={{state, dispatch}}>
			<Component {...pageProps} />
		</Context.Provider>
	)
}






export default MyApp
