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
			<h1>{state.current_count}</h1>
			<button onClick={()=>dispatch({type:ActionType.INC, payload: 1})}>increment by Root</button>
			<C1/>
			<Component {...pageProps} />
		</Context.Provider>
	)
}



const C1 = () => {
	return (
		<>
			<h1>C1 </h1>
			<C2 />
		</>
	)
}

const C2 = () => {
	const ctx = useContext(Context) 
	return (
		<>
			<h1>C2:{ctx.state.current_count} </h1>
			<button onClick={()=>ctx.dispatch({type: ActionType.DEC, payload: 1})}>decrement by C2 </button>
		</>
	)
}



export default MyApp
