import Header from "components/Header"

const Layout = ({children}) => {
	return (
		<div className="container-fluid">
			<Header />
			<main>{children}</main>
		</div>
	)
}
export default Layout
