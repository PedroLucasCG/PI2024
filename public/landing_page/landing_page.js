import get_login_data from "../get_login_data.js";

const value = await get_login_data();
(!value.error ? logged : unlogged).style.display = "block";
