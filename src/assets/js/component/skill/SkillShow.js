import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

export const SkillShow = props => (
    <ShowGuesser {...props}>
        <FieldGuesser source={"name"} />
        <FieldGuesser source={"mandatory"} />
        <FieldGuesser source={"description"} />
    </ShowGuesser>
);