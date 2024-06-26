import React, { Component } from "react";
import ReactDOM from "react-dom";
import axios from "axios";
import Swal from "sweetalert2";
import { sum } from "lodash";

class Cart extends Component {
    constructor(props) {
        super(props);
        this.state = {
            cart: [],
            products: [],
            customers: [],
            barcode: "",
            search: "",
            customer_id: "",
            amount: 0,
            on_process: false,
        };

        this.loadCart = this.loadCart.bind(this);
        this.handleOnChangeBarcode = this.handleOnChangeBarcode.bind(this);
        this.handleScanBarcode = this.handleScanBarcode.bind(this);
        this.handleChangeQty = this.handleChangeQty.bind(this);
        this.handleEmptyCart = this.handleEmptyCart.bind(this);

        this.loadProducts = this.loadProducts.bind(this);
        this.handleChangeSearch = this.handleChangeSearch.bind(this);
        this.handleSeach = this.handleSeach.bind(this);
        this.setCustomerId = this.setCustomerId.bind(this);
        this.handleClickSubmit = this.handleClickSubmit.bind(this);
    }

    componentDidMount() {
        // load user cart
        this.loadCart();
        this.loadProducts();
        this.loadCustomers();
    }

    loadCustomers() {
        axios.get(`/admin/customers`).then((res) => {
            const customers = res.data;
            this.setState({ customers });
        });
    }

    loadProducts(search = "") {
        const query = !!search ? `?search=${search}` : "";
        axios.get(`/admin/products${query}`).then((res) => {
            const products = res.data.data;
            this.setState({ products });
        });
    }

    handleOnChangeBarcode(event) {
        const barcode = event.target.value;
        console.log(barcode);
        this.setState({ barcode });
    }

    loadCart() {
        axios.get("/admin/cart").then((res) => {
            const cart = res.data;
            this.setState({ cart });
        });
    }

    handleScanBarcode(event) {
        event.preventDefault();
        const { barcode } = this.state;
        if (!!barcode) {
            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    this.loadCart();
                    this.setState({ barcode: "" });
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }
    handleChangeQty(product_id, qty) {
        const cart = this.state.cart.map((c) => {
            if (c.id === product_id) {
                c.pivot.quantity = qty;
            }
            return c;
        });

        this.setState({ cart });
        if (!qty) return;

        axios
            .post("/admin/cart/change-qty", { product_id, quantity: qty })
            .then((res) => { })
            .catch((err) => {
                Swal.fire("Error!", err.response.data.message, "error");
            });
    }

    getTotal(cart) {
        const total = cart.map((c) => c.pivot.quantity * c.price);
        return sum(total).toFixed(0);
    }
    handleClickDelete(product_id) {
        axios
            .post("/admin/cart/delete", { product_id, _method: "DELETE" })
            .then((res) => {
                const cart = this.state.cart.filter((c) => c.id !== product_id);
                this.setState({ cart });
            });
    }
    handleEmptyCart() {
        axios.post("/admin/cart/empty", { _method: "DELETE" }).then((res) => {
            this.setState({ cart: [] });
        });
    }
    handleChangeSearch(event) {
        const search = event.target.value;
        this.setState({ search });
    }
    handleSeach(event) {
        if (event.keyCode === 13) {
            this.loadProducts(event.target.value);
        }
    }

    addProductToCart(barcode) {
        let product = this.state.products.find((p) => p.barcode === barcode);
        if (!!product) {
            // if product is already in cart
            let cart = this.state.cart.find((c) => c.id === product.id);
            if (!!cart) {
                // update quantity
                this.setState({
                    cart: this.state.cart.map((c) => {
                        if (
                            c.id === product.id &&
                            product.quantity > c.pivot.quantity
                        ) {
                            c.pivot.quantity = c.pivot.quantity + 1;
                        }
                        return c;
                    }),
                });
            } else {
                if (product.quantity > 0) {
                    product = {
                        ...product,
                        pivot: {
                            quantity: 1,
                            product_id: product.id,
                            user_id: 1,
                        },
                    };

                    this.setState({ cart: [...this.state.cart, product] });
                }
            }

            axios
                .post("/admin/cart", { barcode })
                .then((res) => {
                    // this.loadCart();
                    console.log(res);
                })
                .catch((err) => {
                    Swal.fire("Error!", err.response.data.message, "error");
                });
        }
    }

    setCustomerId(event) {
        this.setState({ customer_id: event.target.value });
    }
    async handleClickSubmit() {
        try {
            if (this.state.amount == '' || this.state.amount <= 0 && this.getTotal(this.state.cart) > this.state.amount) {
                Swal.fire({ title: "Error", 'icon': 'error', 'text': "รับเงินน้อยกว่าราคาสินค้า" })
                return 0;
            }
            const confirmed = await Swal.fire({ title: "Confirm", 'text': "ยืนยันการขาย", showCancelButton: true })
            if (!confirmed.isConfirmed) {
                return 0;
            }

            this.setState({ on_process: true })
            const res = await axios.post("/admin/orders", {
                customer_id: this.state.customer_id,
                amount: this.state.amount,
            });
            this.setState({ amount: 0 })
            this.setState({ on_process: false })
            $('#cart-modal').modal('hide')
            Swal.fire({ title: "เสร็จสิ้น", 'text': 'บันทึกการขายแล้ว', icon: "success" })
            this.loadCart();
            return res.data;
        } catch (err) {
            Swal.showValidationMessage(err.response.data.message);
        }
    }
    render() {
        const { cart, products, customers, barcode } = this.state;
        return (
            <div className="row w-100">
                <div className="col-md-6 col-lg-8">
                    <div className="mb-2">
                        <input
                            type="text"
                            className="form-control"
                            placeholder="ค้นหาผลิตภัณฑ์ ..."
                            onChange={this.handleChangeSearch}
                            onKeyDown={this.handleSeach}
                        />
                    </div>
                    <div className="order-product row">
                        {products.map((p) => (
                            <div
                                onClick={() => this.addProductToCart(p.barcode)}
                                key={p.id}
                                className="col-md-4 col-6"
                            >
                                <img src={p.image} style={{ objectFit: "cover", width: "100%" }} />
                                <h5 className="text-center pt-2">
                                    {p.name}
                                </h5>
                            </div>
                        ))}
                    </div>
                </div>
                <div className="col-md-6 col-lg-4">
                    <div className="row mb-2">
                        <div className="col">
                            <form onSubmit={this.handleScanBarcode}>
                                <input
                                    type="text"
                                    className="form-control"
                                    placeholder="สแกนบาร์โค้ด ..."
                                    value={barcode}
                                    onChange={this.handleOnChangeBarcode}
                                />
                            </form>
                        </div>
                        <div className="col">
                            <select
                                className="form-control"
                                onChange={this.setCustomerId}
                            >
                                <option value="">Walking Customer</option>
                                {customers.map((cus) => (
                                    <option
                                        key={cus.id}
                                        value={cus.id}
                                    >{`${cus.first_name} ${cus.last_name}`}</option>
                                ))}
                            </select>
                        </div>
                    </div>
                    <div className="user-cart">
                        <div className="card">
                            <table className="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ชื่อผลิตภัณฑ์</th>
                                        <th>ปริมาณ</th>
                                        <th className="text-right">ราคา</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {cart.map((c) => (
                                        <tr key={c.id}>
                                            <td>{c.name}</td>
                                            <td>
                                                <input
                                                    type="text"
                                                    className="form-control form-control-sm qty"
                                                    value={c.pivot.quantity}
                                                    onChange={(event) =>
                                                        this.handleChangeQty(
                                                            c.id,
                                                            event.target.value
                                                        )
                                                    }
                                                />
                                                <button
                                                    className="btn btn-danger btn-sm"
                                                    onClick={() =>
                                                        this.handleClickDelete(
                                                            c.id
                                                        )
                                                    }
                                                >
                                                    <i className="fas fa-trash"></i>
                                                </button>
                                            </td>
                                            <td className="text-right">
                                                {window.APP.currency_symbol}{" "}
                                                {(
                                                    c.price * c.pivot.quantity
                                                ).toFixed(2)}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div className="row">
                        <div className="col">Total:</div>
                        <div className="col text-right">
                            {window.APP.currency_symbol} {this.getTotal(cart)}
                        </div>
                    </div>
                    <div className="row">
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-danger btn-block"
                                onClick={this.handleEmptyCart}
                                disabled={!cart.length}
                            >
                                ยกเลิก
                            </button>
                        </div>
                        <div className="col">
                            <button
                                type="button"
                                className="btn btn-primary btn-block"
                                disabled={!cart.length}
                                data-toggle="modal" data-target="#cart-modal"
                                onClick={() => this.setState({ amount: this.getTotal(this.state.cart) })}
                            >
                                ยืนยันรายการ
                            </button>
                        </div>
                    </div>
                </div>


                <div className="modal fade" id="cart-modal" tabIndex="-1" role="dialog" aria-labelledby="modelTitleId" data-backdrop="static" aria-hidden="true">
                    <div className="modal-dialog modal-dialog-centered" role="document">
                        {!this.state.on_process && <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">คิดเงิน</h5>
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                <h4>ราคาสินค้า {this.getTotal(this.state.cart)} บาท</h4>
                                <div className="form-group">
                                    <label>รับเงิน</label>
                                    <input type="number" className="form-control" value={this.state.amount} onChange={(e) => { this.setState({ amount: e.target.value }) }} />
                                </div>
                                <h4>เงินทอน : {this.state.amount - this.getTotal(this.state.cart)}</h4>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button onClick={() => this.handleClickSubmit()} type="button" className="btn btn-primary">Submit</button>
                            </div>
                        </div>}
                        {this.state.on_process && <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title">คิดเงิน</h5>
                            </div>
                            <div className="modal-body">
                                <img className="w-100" src="/images/loading.gif" />
                            </div>
                        </div>}
                    </div>
                </div>
            </div>
        );
    }
}

export default Cart;

if (document.getElementById("cart")) {
    ReactDOM.render(<Cart />, document.getElementById("cart"));
}
