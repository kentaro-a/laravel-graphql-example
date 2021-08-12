import Action from "../Action"
import State from "../State"

const f = (state: State, action: Action): State => {
	state = {...state, current_count: state.current_count - action.payload}
	return state
}
export default f


