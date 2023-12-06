import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

export const UserShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"username"} />
        <FieldGuesser source={"roles"} />
        <FieldGuesser source={"email"} />
    </ShowGuesser>
);