import {FieldGuesser} from "@api-platform/admin";
import {Datagrid, InfiniteList} from "react-admin";

export const UserList = props => (
    <InfiniteList {...props}>
        <Datagrid>
            <FieldGuesser source={"username"} />
            <FieldGuesser source={"roles"} />
            <FieldGuesser source={"email"} />
        </Datagrid>
    </InfiniteList>
);